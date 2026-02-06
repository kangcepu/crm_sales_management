import 'package:flutter/foundation.dart';
import '../core/api_config.dart';
import '../models/media_item.dart';
import '../models/store.dart';
import '../services/location_service.dart';
import '../services/report_service.dart';
import '../services/store_service.dart';
import '../services/visit_service.dart';

class ReportViewModel extends ChangeNotifier {
  ReportViewModel(this._storeService, this._visitService, this._reportService, this._locationService);

  final StoreService _storeService;
  final VisitService _visitService;
  final ReportService _reportService;
  final LocationService _locationService;

  List<Store> stores = [];
  Store? selectedStore;
  bool isBusy = false;
  String? message;

  Future<void> loadStores() async {
    try {
      final fetched = await _storeService.fetchStores();
      final unique = <int, Store>{};
      for (final store in fetched) {
        unique[store.id] = store;
      }
      stores = unique.values.toList();
      if (selectedStore != null) {
        final match = stores.where((item) => item.id == selectedStore!.id).toList();
        if (match.isNotEmpty) {
          selectedStore = match.first;
        } else if (stores.isNotEmpty) {
          selectedStore = stores.first;
        } else {
          selectedStore = null;
        }
      } else if (stores.isNotEmpty) {
        selectedStore = stores.first;
      }
    } catch (_) {}
    notifyListeners();
  }

  void selectStore(Store? store) {
    selectedStore = store;
    notifyListeners();
  }

  Future<bool> submitReport({
    required Map<String, dynamic> reportPayload,
    required String summary,
    required String? nextVisitPlan,
    required int userId,
    required List<MediaItem> mediaItems,
  }) async {
    if (selectedStore == null) {
      message = 'Select a store';
      notifyListeners();
      return false;
    }

    isBusy = true;
    message = null;
    notifyListeners();

    try {
      final storeLat = selectedStore?.address?.latitude;
      final storeLng = selectedStore?.address?.longitude;
      if (storeLat == null || storeLng == null) {
        message = 'Store location not set by admin';
        isBusy = false;
        notifyListeners();
        return false;
      }

      final locationResult = await _locationService.getVerifiedPosition();
      if (!locationResult.isValid) {
        message = locationResult.error ?? 'Location unavailable';
        isBusy = false;
        notifyListeners();
        return false;
      }

      final position = locationResult.position!;
      final distance = _locationService.distanceBetween(position.latitude, position.longitude, storeLat, storeLng);
      final visitStatus = distance > ApiConfig.locationToleranceMeters ? 'OUT_OF_RANGE' : 'ON_TIME';

      final visitPayload = {
        'store_id': selectedStore!.id,
        'user_id': userId,
        'visit_at': DateTime.now().toIso8601String(),
        'latitude': position.latitude,
        'longitude': position.longitude,
        'distance_from_store': distance,
        'visit_status': visitStatus,
        'summary': summary,
        'next_visit_plan': nextVisitPlan,
      };

      final visit = await _visitService.createVisit(visitPayload);
      final visitId = visit['id'];
      if (visitId == null) {
        message = 'Failed to create visit';
        isBusy = false;
        notifyListeners();
        return false;
      }

      final fields = Map<String, dynamic>.from(reportPayload);
      fields['visit_id'] = visitId;
      await _reportService.createReport(fields, mediaItems);
      message = visitStatus == 'OUT_OF_RANGE'
          ? 'Report saved (Out of Range: ${distance.toStringAsFixed(1)} m)'
          : 'Report saved';
      isBusy = false;
      notifyListeners();
      return true;
    } catch (e) {
      message = e.toString();
      isBusy = false;
      notifyListeners();
      return false;
    }
  }
}

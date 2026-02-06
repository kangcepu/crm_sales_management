import 'package:flutter/foundation.dart';
import '../models/media_item.dart';
import '../services/location_service.dart';
import '../services/store_service.dart';
import '../services/visit_service.dart';

class StoreInputViewModel extends ChangeNotifier {
  StoreInputViewModel(this._storeService, this._visitService, this._locationService);

  final StoreService _storeService;
  final VisitService _visitService;
  final LocationService _locationService;

  bool isBusy = false;
  String? message;

  Future<bool> submit({
    required Map<String, dynamic> storePayload,
    required List<MediaItem> mediaItems,
    required int userId,
  }) async {
    isBusy = true;
    message = null;
    notifyListeners();
    try {
      final store = await _storeService.createStore(storePayload);
      if (mediaItems.isNotEmpty) {
        final position = await _locationService.getCurrentPosition();
        final visitPayload = {
          'store_id': store.id,
          'user_id': userId,
          'visit_at': DateTime.now().toIso8601String(),
          'latitude': position?.latitude,
          'longitude': position?.longitude,
          'distance_from_store': null,
          'visit_status': 'ON_TIME',
          'summary': 'Initial store input',
          'next_visit_plan': null,
        };
        final visit = await _visitService.createVisit(visitPayload);
        if (visit['id'] != null) {
          await _storeService.uploadStoreMedia(visit['id'], mediaItems);
        }
      }
      message = 'Store saved';
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

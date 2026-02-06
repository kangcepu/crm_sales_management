import 'package:flutter/foundation.dart';
import '../services/settings_service.dart';

class SettingsViewModel extends ChangeNotifier {
  SettingsViewModel(this._service);

  final SettingsService _service;
  String? siteTitle;
  String? siteDescription;
  String? siteLogo;

  Future<void> load() async {
    try {
      final data = await _service.fetchSettings();
      siteTitle = data['site_title']?.toString();
      siteDescription = data['site_description']?.toString();
      siteLogo = data['site_logo']?.toString();
    } catch (_) {}
    notifyListeners();
  }
}

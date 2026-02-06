import 'dart:convert';
import 'package:flutter/foundation.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:image_picker/image_picker.dart';
import '../core/storage_keys.dart';
import '../models/user.dart';
import '../services/api_client.dart';
import '../services/auth_service.dart';
import '../services/profile_service.dart';

class AuthViewModel extends ChangeNotifier {
  AuthViewModel(this.client)
      : _authService = AuthService(client),
        _profileService = ProfileService(client);

  final ApiClient client;
  final AuthService _authService;
  final ProfileService _profileService;
  User? user;
  String? token;
  bool isBusy = false;

  bool get isAuthenticated => token != null && token!.isNotEmpty;

  Future<void> loadSession() async {
    final prefs = await SharedPreferences.getInstance();
    token = prefs.getString(StorageKeys.token);
    if (token != null && token!.isNotEmpty) {
      client.token = token;
    }
    final rawUser = prefs.getString(StorageKeys.user);
    if (rawUser != null) {
      try {
        final decoded = jsonDecode(rawUser);
        if (decoded is Map<String, dynamic>) {
          user = User.fromJson(decoded);
        }
      } catch (_) {}
    }
    notifyListeners();
  }

  Future<bool> login(String email, String password) async {
    isBusy = true;
    notifyListeners();
    try {
      final result = await _authService.login(email, password);
      token = result.token;
      user = result.user;
      client.token = token;
      await _saveUser();
      isBusy = false;
      notifyListeners();
      return true;
    } catch (_) {
      isBusy = false;
      notifyListeners();
      return false;
    }
  }

  Future<void> logout() async {
    isBusy = true;
    notifyListeners();
    await _authService.logout();
    token = null;
    user = null;
    client.token = null;
    isBusy = false;
    notifyListeners();
  }

  Future<bool> updateProfile({String? fullName, String? phone, String? password, XFile? photo}) async {
    isBusy = true;
    notifyListeners();
    try {
      user = await _profileService.updateProfile(
        fullName: fullName,
        phone: phone,
        password: password,
        photo: photo,
      );
      await _saveUser();
      isBusy = false;
      notifyListeners();
      return true;
    } catch (_) {
      isBusy = false;
      notifyListeners();
      return false;
    }
  }

  Future<void> _saveUser() async {
    if (user == null) return;
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString(StorageKeys.user, jsonEncode(user!.toJson()));
  }
}

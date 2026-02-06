import 'dart:convert';
import 'package:shared_preferences/shared_preferences.dart';
import '../core/storage_keys.dart';
import '../models/user.dart';
import 'api_client.dart';

class AuthResult {
  final String token;
  final User user;

  AuthResult({required this.token, required this.user});
}

class AuthService {
  AuthService(this.client);

  final ApiClient client;

  Future<AuthResult> login(String email, String password) async {
    final data = await client.post('/login', {'email': email, 'password': password});
    final token = data['token'].toString();
    final user = User.fromJson(data['user']);
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString(StorageKeys.token, token);
    await prefs.setString(StorageKeys.user, jsonEncode(user.toJson()));
    return AuthResult(token: token, user: user);
  }

  Future<void> logout() async {
    try {
      await client.post('/logout', {});
    } catch (_) {}
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove(StorageKeys.token);
    await prefs.remove(StorageKeys.user);
  }
}

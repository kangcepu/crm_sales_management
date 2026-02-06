import 'api_client.dart';

class SettingsService {
  SettingsService(this.client);

  final ApiClient client;

  Future<Map<String, dynamic>> fetchSettings() async {
    final data = await client.get('/settings');
    if (data is Map<String, dynamic>) {
      return data;
    }
    return {};
  }
}

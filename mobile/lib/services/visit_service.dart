import 'api_client.dart';

class VisitService {
  VisitService(this.client);

  final ApiClient client;

  Future<Map<String, dynamic>> createVisit(Map<String, dynamic> payload) async {
    final data = await client.post('/store-visits', payload);
    if (data is Map<String, dynamic>) {
      return data;
    }
    return {};
  }
}

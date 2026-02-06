import '../models/media_item.dart';
import 'api_client.dart';
import 'media_helper.dart';

class ReportService {
  ReportService(this.client);

  final ApiClient client;

  Future<Map<String, dynamic>> createReport(Map<String, dynamic> fields, List<MediaItem> mediaItems) async {
    if (mediaItems.isEmpty) {
      final data = await client.post('/store-visit-reports', fields);
      return data is Map<String, dynamic> ? data : {};
    }
    final files = await buildMultipartFiles(mediaItems, 'files[]');
    final stringFields = fields.map((key, value) => MapEntry(key, value.toString()));
    final data = await client.multipart('/store-visit-reports', stringFields, files);
    if (data is Map<String, dynamic>) {
      return data;
    }
    return {};
  }
}

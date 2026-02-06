import '../models/store.dart';
import '../models/media_item.dart';
import 'api_client.dart';
import 'media_helper.dart';

class StoreService {
  StoreService(this.client);

  final ApiClient client;

  Future<List<Store>> fetchStores() async {
    final data = await client.get('/stores');
    if (data is List) {
      return data.map((e) => Store.fromJson(e)).toList();
    }
    return [];
  }

  Future<Store> createStore(Map<String, dynamic> payload) async {
    final data = await client.post('/stores', payload);
    return Store.fromJson(data);
  }

  Future<void> uploadStoreMedia(int visitId, List<MediaItem> mediaItems) async {
    if (mediaItems.isEmpty) {
      return;
    }
    final files = await buildMultipartFiles(mediaItems, 'files[]');
    await client.multipart('/store-media', {
      'visit_id': visitId.toString(),
      'taken_at': DateTime.now().toIso8601String(),
    }, files);
  }
}

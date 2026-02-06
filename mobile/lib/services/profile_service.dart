import 'package:image_picker/image_picker.dart';
import 'package:http/http.dart' as http;
import '../models/user.dart';
import 'api_client.dart';

class ProfileService {
  ProfileService(this.client);

  final ApiClient client;

  Future<User> fetchMe() async {
    final data = await client.get('/me');
    return User.fromJson(data);
  }

  Future<User> updateProfile({
    String? fullName,
    String? phone,
    String? password,
    XFile? photo,
  }) async {
    if (photo != null) {
      final fields = <String, String>{};
      if (fullName != null) fields['full_name'] = fullName;
      if (phone != null) fields['phone'] = phone;
      if (password != null && password.isNotEmpty) fields['password'] = password;
      final file = await MultipartHelper.fromXFile(photo, 'photo');
      final data = await client.multipart('/me', fields, [file]);
      return User.fromJson(data);
    }
    final payload = <String, dynamic>{};
    if (fullName != null) payload['full_name'] = fullName;
    if (phone != null) payload['phone'] = phone;
    if (password != null && password.isNotEmpty) payload['password'] = password;
    final data = await client.post('/me', payload);
    return User.fromJson(data);
  }
}

class MultipartHelper {
  static Future<http.MultipartFile> fromXFile(XFile file, String field) async {
    return http.MultipartFile.fromPath(field, file.path);
  }
}

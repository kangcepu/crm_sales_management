import 'dart:io';
import 'package:http/http.dart' as http;
import 'package:http_parser/http_parser.dart';
import 'package:mime/mime.dart';
import '../models/media_item.dart';

Future<List<http.MultipartFile>> buildMultipartFiles(List<MediaItem> items, String field) async {
  final files = <http.MultipartFile>[];
  for (final item in items) {
    final file = File(item.file.path);
    final mime = lookupMimeType(file.path) ?? 'application/octet-stream';
    final parts = mime.split('/');
    final multipart = await http.MultipartFile.fromPath(
      field,
      file.path,
      contentType: MediaType(parts[0], parts.length > 1 ? parts[1] : 'octet-stream'),
    );
    files.add(multipart);
  }
  return files;
}

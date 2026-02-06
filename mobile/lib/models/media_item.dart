import 'package:image_picker/image_picker.dart';

class MediaItem {
  final XFile file;
  final String type;
  final DateTime capturedAt;

  MediaItem({
    required this.file,
    required this.type,
    required this.capturedAt,
  });
}

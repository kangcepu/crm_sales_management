import 'package:flutter/material.dart';
import '../models/media_item.dart';

class MediaPreview extends StatelessWidget {
  const MediaPreview({super.key, required this.items, required this.onRemove});

  final List<MediaItem> items;
  final void Function(int index) onRemove;

  @override
  Widget build(BuildContext context) {
    if (items.isEmpty) {
      return const SizedBox.shrink();
    }
    return Wrap(
      spacing: 12,
      runSpacing: 12,
      children: List.generate(items.length, (index) {
        final item = items[index];
        return Stack(
          children: [
            Container(
              width: 96,
              height: 76,
              decoration: BoxDecoration(
                borderRadius: BorderRadius.circular(12),
                color: Colors.black12,
              ),
              child: Center(
                child: Text(
                  item.type,
                  style: const TextStyle(fontSize: 12, fontWeight: FontWeight.w600),
                ),
              ),
            ),
            Positioned(
              top: -4,
              right: -4,
              child: IconButton(
                icon: const Icon(Icons.close, size: 18),
                onPressed: () => onRemove(index),
              ),
            )
          ],
        );
      }),
    );
  }
}

import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';
import 'package:provider/provider.dart';
import '../core/app_colors.dart';
import '../models/media_item.dart';
import '../services/location_service.dart';
import '../viewmodels/auth_viewmodel.dart';
import '../viewmodels/store_input_viewmodel.dart';
import '../widgets/app_button.dart';
import '../widgets/app_input.dart';

class StoreInputPage extends StatefulWidget {
  const StoreInputPage({super.key});

  @override
  State<StoreInputPage> createState() => _StoreInputPageState();
}

class _StoreInputPageState extends State<StoreInputPage> {
  final erpController = TextEditingController();
  final codeController = TextEditingController();
  final nameController = TextEditingController();
  final ownerController = TextEditingController();
  final phoneController = TextEditingController();
  final addressController = TextEditingController();
  final cityController = TextEditingController();
  final provinceController = TextEditingController();
  final latitudeController = TextEditingController();
  final longitudeController = TextEditingController();
  String storeType = 'REGULAR';
  bool isActive = true;
  final mediaItems = <MediaItem>[];
  final picker = ImagePicker();

  @override
  void dispose() {
    erpController.dispose();
    codeController.dispose();
    nameController.dispose();
    ownerController.dispose();
    phoneController.dispose();
    addressController.dispose();
    cityController.dispose();
    provinceController.dispose();
    latitudeController.dispose();
    longitudeController.dispose();
    super.dispose();
  }

  Future<void> pickPhoto() async {
    final file = await picker.pickImage(source: ImageSource.camera, imageQuality: 50);
    if (file != null) {
      setState(() {
        mediaItems.add(MediaItem(file: file, type: 'PHOTO', capturedAt: DateTime.now()));
      });
    }
  }

  Future<void> pickVideo() async {
    final file = await picker.pickVideo(source: ImageSource.camera);
    if (file != null) {
      setState(() {
        mediaItems.add(MediaItem(file: file, type: 'VIDEO', capturedAt: DateTime.now()));
      });
    }
  }

  Future<void> fillCoordinates() async {
    final location = LocationService();
    final position = await location.getCurrentPosition();
    if (position != null) {
      latitudeController.text = position.latitude.toStringAsFixed(7);
      longitudeController.text = position.longitude.toStringAsFixed(7);
      setState(() {});
    }
  }

  Future<void> submit() async {
    final auth = context.read<AuthViewModel>();
    final userId = auth.user?.id;
    if (userId == null) {
      ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('User not available')));
      return;
    }
    final payload = {
      'erp_store_id': erpController.text.trim().isEmpty ? null : erpController.text.trim(),
      'store_code': codeController.text.trim(),
      'store_name': nameController.text.trim(),
      'store_type': storeType,
      'owner_name': ownerController.text.trim(),
      'phone': phoneController.text.trim(),
      'is_active': isActive,
      'address': {
        'address': addressController.text.trim(),
        'city': cityController.text.trim(),
        'province': provinceController.text.trim(),
        'latitude': latitudeController.text.trim().isEmpty ? null : double.tryParse(latitudeController.text.trim()),
        'longitude': longitudeController.text.trim().isEmpty ? null : double.tryParse(longitudeController.text.trim()),
      }
    };

    final vm = context.read<StoreInputViewModel>();
    final success = await vm.submit(storePayload: payload, mediaItems: mediaItems, userId: userId);
    if (!mounted) return;
    ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(vm.message ?? 'Done')));
    if (success) {
      codeController.clear();
      nameController.clear();
      ownerController.clear();
      phoneController.clear();
      addressController.clear();
      cityController.clear();
      provinceController.clear();
      latitudeController.clear();
      longitudeController.clear();
      erpController.clear();
      setState(() {
        storeType = 'REGULAR';
        isActive = true;
        mediaItems.clear();
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    final vm = context.watch<StoreInputViewModel>();
    return SingleChildScrollView(
      padding: const EdgeInsets.fromLTRB(20, 20, 20, 28),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text('Input Toko', style: Theme.of(context).textTheme.titleLarge?.copyWith(color: AppColors.ink)),
          const SizedBox(height: 6),
          Text('Lengkapi informasi toko', style: Theme.of(context).textTheme.bodySmall?.copyWith(color: AppColors.muted)),
          const SizedBox(height: 18),
          _SectionCard(
            title: 'Informasi Dasar',
            child: Column(
              children: [
                AppInput(label: 'ERP Store ID', controller: erpController),
                const SizedBox(height: 12),
                AppInput(label: 'Store Code', controller: codeController),
                const SizedBox(height: 12),
                AppInput(label: 'Store Name', controller: nameController),
                const SizedBox(height: 12),
                DropdownButtonFormField<String>(
                  key: ValueKey(storeType),
                  initialValue: storeType,
                  items: const [
                    DropdownMenuItem(value: 'REGULAR', child: Text('REGULAR')),
                    DropdownMenuItem(value: 'CONSIGNMENT', child: Text('CONSIGNMENT')),
                  ],
                  onChanged: (value) => setState(() => storeType = value ?? 'REGULAR'),
                  decoration: const InputDecoration(labelText: 'Store Type'),
                ),
              ],
            ),
          ),
          const SizedBox(height: 16),
          _SectionCard(
            title: 'Kontak & Status',
            child: Column(
              children: [
                AppInput(label: 'Owner Name', controller: ownerController),
                const SizedBox(height: 12),
                AppInput(label: 'Phone', controller: phoneController, keyboardType: TextInputType.phone),
                const SizedBox(height: 6),
                SwitchListTile.adaptive(
                  value: isActive,
                  onChanged: (value) => setState(() => isActive = value),
                  title: const Text('Active'),
                  contentPadding: EdgeInsets.zero,
                ),
              ],
            ),
          ),
          const SizedBox(height: 16),
          _SectionCard(
            title: 'Alamat & Koordinat',
            child: Column(
              children: [
                AppInput(label: 'Address', controller: addressController),
                const SizedBox(height: 12),
                AppInput(label: 'City', controller: cityController),
                const SizedBox(height: 12),
                AppInput(label: 'Province', controller: provinceController),
                const SizedBox(height: 12),
                Row(
                  children: [
                    Expanded(
                      child: AppInput(
                        label: 'Latitude',
                        controller: latitudeController,
                        keyboardType: TextInputType.number,
                      ),
                    ),
                    const SizedBox(width: 12),
                    Expanded(
                      child: AppInput(
                        label: 'Longitude',
                        controller: longitudeController,
                        keyboardType: TextInputType.number,
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 10),
                SizedBox(
                  width: double.infinity,
                  child: OutlinedButton.icon(
                    onPressed: fillCoordinates,
                    icon: const Icon(Icons.my_location),
                    label: const Text('Use Current Location'),
                  ),
                ),
              ],
            ),
          ),
          const SizedBox(height: 16),
          _SectionCard(
            title: 'Media',
            subtitle: 'Tambahkan foto atau video toko',
            child: Column(
              children: [
                Row(
                  children: [
                    Expanded(
                      child: OutlinedButton.icon(
                        onPressed: pickPhoto,
                        icon: const Icon(Icons.photo_camera),
                        label: const Text('Add Photo'),
                      ),
                    ),
                    const SizedBox(width: 12),
                    Expanded(
                      child: OutlinedButton.icon(
                        onPressed: pickVideo,
                        icon: const Icon(Icons.videocam),
                        label: const Text('Add Video'),
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 8),
                ...mediaItems.asMap().entries.map((entry) {
                  final item = entry.value;
                  return ListTile(
                    contentPadding: EdgeInsets.zero,
                    title: Text(item.type, style: const TextStyle(color: AppColors.ink, fontWeight: FontWeight.w600)),
                    subtitle: Text(item.capturedAt.toString(), style: const TextStyle(color: AppColors.muted)),
                    trailing: IconButton(
                      icon: const Icon(Icons.delete),
                      onPressed: () => setState(() => mediaItems.removeAt(entry.key)),
                    ),
                  );
                }),
              ],
            ),
          ),
          const SizedBox(height: 20),
          AppButton(
            label: 'Save Store',
            onPressed: vm.isBusy ? null : submit,
            isLoading: vm.isBusy,
          ),
        ],
      ),
    );
  }
}

class _SectionCard extends StatelessWidget {
  const _SectionCard({required this.title, required this.child, this.subtitle});

  final String title;
  final String? subtitle;
  final Widget child;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(18),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withAlpha(10),
            blurRadius: 12,
            offset: const Offset(0, 6),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(title, style: Theme.of(context).textTheme.titleMedium?.copyWith(color: AppColors.ink)),
          if (subtitle != null) ...[
            const SizedBox(height: 4),
            Text(subtitle!, style: Theme.of(context).textTheme.bodySmall?.copyWith(color: AppColors.muted)),
          ],
          const SizedBox(height: 14),
          child,
        ],
      ),
    );
  }
}

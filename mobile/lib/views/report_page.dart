import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';
import 'package:provider/provider.dart';
import '../core/api_config.dart';
import '../core/app_colors.dart';
import '../models/media_item.dart';
import '../models/store.dart';
import '../viewmodels/auth_viewmodel.dart';
import '../viewmodels/report_viewmodel.dart';
import '../widgets/app_button.dart';
import '../widgets/app_input.dart';

class ReportPage extends StatefulWidget {
  const ReportPage({super.key});

  @override
  State<ReportPage> createState() => _ReportPageState();
}

class _ReportPageState extends State<ReportPage> {
  final summaryController = TextEditingController();
  final nextPlanController = TextEditingController();
  final consignmentQtyController = TextEditingController(text: '0');
  final consignmentValueController = TextEditingController(text: '0');
  final salesQtyController = TextEditingController(text: '0');
  final salesValueController = TextEditingController(text: '0');
  final competitorController = TextEditingController();
  final notesController = TextEditingController();
  String paymentStatus = 'PENDING';
  final mediaItems = <MediaItem>[];
  final picker = ImagePicker();

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      context.read<ReportViewModel>().loadStores();
    });
  }

  @override
  void dispose() {
    summaryController.dispose();
    nextPlanController.dispose();
    consignmentQtyController.dispose();
    consignmentValueController.dispose();
    salesQtyController.dispose();
    salesValueController.dispose();
    competitorController.dispose();
    notesController.dispose();
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

  Future<void> submit() async {
    final auth = context.read<AuthViewModel>();
    final userId = auth.user?.id;
    if (userId == null) {
      ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('User not available')));
      return;
    }

    final reportPayload = {
      'consignment_qty': int.tryParse(consignmentQtyController.text.trim()) ?? 0,
      'consignment_value': double.tryParse(consignmentValueController.text.trim()) ?? 0,
      'sales_qty': int.tryParse(salesQtyController.text.trim()) ?? 0,
      'sales_value': double.tryParse(salesValueController.text.trim()) ?? 0,
      'payment_status': paymentStatus,
      'competitor_activity': competitorController.text.trim().isEmpty ? null : competitorController.text.trim(),
      'notes': notesController.text.trim().isEmpty ? null : notesController.text.trim(),
    };

    final vm = context.read<ReportViewModel>();
    final success = await vm.submitReport(
      reportPayload: reportPayload,
      summary: summaryController.text.trim(),
      nextVisitPlan: nextPlanController.text.trim().isEmpty ? null : nextPlanController.text.trim(),
      userId: userId,
      mediaItems: mediaItems,
    );

    if (!mounted) return;
    ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(vm.message ?? 'Done')));
    if (success) {
      summaryController.clear();
      nextPlanController.clear();
      consignmentQtyController.text = '0';
      consignmentValueController.text = '0';
      salesQtyController.text = '0';
      salesValueController.text = '0';
      competitorController.clear();
      notesController.clear();
      setState(() {
        paymentStatus = 'PENDING';
        mediaItems.clear();
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    final vm = context.watch<ReportViewModel>();
    return SingleChildScrollView(
      padding: const EdgeInsets.fromLTRB(20, 20, 20, 28),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text('Update Report Store', style: Theme.of(context).textTheme.titleLarge?.copyWith(color: AppColors.ink)),
          const SizedBox(height: 6),
          Text('Lengkapi laporan kunjungan', style: Theme.of(context).textTheme.bodySmall?.copyWith(color: AppColors.muted)),
          const SizedBox(height: 18),
          _SectionCard(
            title: 'Store',
            child: Column(
              children: [
                DropdownButtonFormField<Store>(
                  key: ValueKey(vm.selectedStore?.id ?? 'none'),
                  initialValue: vm.selectedStore,
                  items: vm.stores
                      .map((store) => DropdownMenuItem(value: store, child: Text(store.storeName)))
                      .toList(),
                  onChanged: vm.selectStore,
                  decoration: const InputDecoration(labelText: 'Store'),
                ),
                const SizedBox(height: 8),
                if (vm.selectedStore?.address?.latitude != null && vm.selectedStore?.address?.longitude != null)
                  Align(
                    alignment: Alignment.centerLeft,
                    child: Text(
                      'Koordinat: ${vm.selectedStore!.address!.latitude}, ${vm.selectedStore!.address!.longitude} (toleransi ${ApiConfig.locationToleranceMeters}m)',
                      style: Theme.of(context).textTheme.bodySmall?.copyWith(color: AppColors.muted),
                    ),
                  ),
              ],
            ),
          ),
          const SizedBox(height: 16),
          _SectionCard(
            title: 'Ringkasan',
            child: Column(
              children: [
                AppInput(label: 'Summary', controller: summaryController),
                const SizedBox(height: 12),
                AppInput(label: 'Next Visit Plan', controller: nextPlanController),
              ],
            ),
          ),
          const SizedBox(height: 16),
          _SectionCard(
            title: 'Penjualan',
            child: Column(
              children: [
                Row(
                  children: [
                    Expanded(child: AppInput(label: 'Consignment Qty', controller: consignmentQtyController, keyboardType: TextInputType.number)),
                    const SizedBox(width: 12),
                    Expanded(child: AppInput(label: 'Consignment Value', controller: consignmentValueController, keyboardType: TextInputType.number)),
                  ],
                ),
                const SizedBox(height: 12),
                Row(
                  children: [
                    Expanded(child: AppInput(label: 'Sales Qty', controller: salesQtyController, keyboardType: TextInputType.number)),
                    const SizedBox(width: 12),
                    Expanded(child: AppInput(label: 'Sales Value', controller: salesValueController, keyboardType: TextInputType.number)),
                  ],
                ),
                const SizedBox(height: 12),
                DropdownButtonFormField<String>(
                  key: ValueKey(paymentStatus),
                  initialValue: paymentStatus,
                  items: const [
                    DropdownMenuItem(value: 'PAID', child: Text('PAID')),
                    DropdownMenuItem(value: 'PENDING', child: Text('PENDING')),
                  ],
                  onChanged: (value) => setState(() => paymentStatus = value ?? 'PENDING'),
                  decoration: const InputDecoration(labelText: 'Payment Status'),
                ),
              ],
            ),
          ),
          const SizedBox(height: 16),
          _SectionCard(
            title: 'Catatan',
            child: Column(
              children: [
                AppInput(label: 'Competitor Activity', controller: competitorController),
                const SizedBox(height: 12),
                AppInput(label: 'Notes', controller: notesController),
              ],
            ),
          ),
          const SizedBox(height: 16),
          _SectionCard(
            title: 'Media',
            subtitle: 'Camera only, quality 50%',
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
            label: 'Submit Report',
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

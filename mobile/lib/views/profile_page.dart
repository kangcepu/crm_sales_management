import 'dart:io';
import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';
import 'package:provider/provider.dart';
import '../core/app_colors.dart';
import '../viewmodels/auth_viewmodel.dart';
import '../widgets/app_button.dart';
import '../widgets/app_input.dart';
import 'login_page.dart';

class ProfilePage extends StatefulWidget {
  const ProfilePage({super.key});

  @override
  State<ProfilePage> createState() => _ProfilePageState();
}

class _ProfilePageState extends State<ProfilePage> {
  final nameController = TextEditingController();
  final phoneController = TextEditingController();
  final passwordController = TextEditingController();
  XFile? selectedPhoto;
  final picker = ImagePicker();

  @override
  void dispose() {
    nameController.dispose();
    phoneController.dispose();
    passwordController.dispose();
    super.dispose();
  }

  @override
  void didChangeDependencies() {
    final auth = context.read<AuthViewModel>();
    nameController.text = auth.user?.fullName ?? '';
    phoneController.text = auth.user?.phone ?? '';
    super.didChangeDependencies();
  }

  Future<void> pickPhoto() async {
    final photo = await picker.pickImage(source: ImageSource.gallery, imageQuality: 70);
    if (photo != null) {
      setState(() => selectedPhoto = photo);
    }
  }

  Future<void> save() async {
    final auth = context.read<AuthViewModel>();
    final success = await auth.updateProfile(
      fullName: nameController.text.trim(),
      phone: phoneController.text.trim(),
      password: passwordController.text.trim().isEmpty ? null : passwordController.text.trim(),
      photo: selectedPhoto,
    );
    if (!mounted) return;
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(content: Text(success ? 'Profile updated' : 'Failed to update profile')),
    );
    if (success) {
      setState(() => selectedPhoto = null);
      passwordController.clear();
    }
  }

  @override
  Widget build(BuildContext context) {
    final auth = context.watch<AuthViewModel>();
    final theme = Theme.of(context);

    return ListView(
      padding: const EdgeInsets.all(20),
      children: [
        Text('Profil', style: theme.textTheme.titleLarge?.copyWith(color: AppColors.ink)),
        const SizedBox(height: 16),
        Container(
          padding: const EdgeInsets.all(20),
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.circular(18),
            boxShadow: [
              BoxShadow(
                color: Colors.black.withAlpha(12),
                blurRadius: 14,
                offset: const Offset(0, 8),
              ),
            ],
          ),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Center(
                child: GestureDetector(
                  onTap: pickPhoto,
                  child: CircleAvatar(
                    radius: 42,
                    backgroundColor: const Color(0xFFE0E7FF),
                    backgroundImage: selectedPhoto != null
                        ? FileImage(File(selectedPhoto!.path))
                        : (auth.user?.profilePhotoUrl != null && auth.user!.profilePhotoUrl!.isNotEmpty)
                            ? NetworkImage(auth.user!.profilePhotoUrl!) as ImageProvider
                            : null,
                    child: (auth.user?.profilePhotoUrl == null || auth.user!.profilePhotoUrl!.isEmpty) && selectedPhoto == null
                        ? Text(
                            (auth.user?.fullName ?? 'U').substring(0, 1).toUpperCase(),
                            style: const TextStyle(fontWeight: FontWeight.w700, color: AppColors.primary),
                          )
                        : null,
                  ),
                ),
              ),
              const SizedBox(height: 16),
              AppInput(label: 'Full Name', controller: nameController),
              const SizedBox(height: 12),
              AppInput(label: 'Phone', controller: phoneController),
              const SizedBox(height: 12),
              AppInput(label: 'New Password', controller: passwordController, obscureText: true),
              const SizedBox(height: 16),
              AppButton(
                label: 'Save Profile',
                onPressed: auth.isBusy ? null : save,
                isLoading: auth.isBusy,
              ),
            ],
          ),
        ),
        const SizedBox(height: 20),
        AppButton(
          label: 'Logout',
          onPressed: () async {
            final navigator = Navigator.of(context);
            await auth.logout();
            if (!context.mounted) return;
            navigator.pushAndRemoveUntil(
              MaterialPageRoute(builder: (_) => const LoginPage()),
              (_) => false,
            );
          },
          backgroundColor: const Color(0xFF101828),
        ),
      ],
    );
  }
}

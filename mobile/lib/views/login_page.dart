import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../core/app_colors.dart';
import '../viewmodels/auth_viewmodel.dart';
import '../viewmodels/settings_viewmodel.dart';
import '../widgets/app_button.dart';
import '../widgets/app_input.dart';
import 'home_page.dart';

class LoginPage extends StatefulWidget {
  const LoginPage({super.key});

  @override
  State<LoginPage> createState() => _LoginPageState();
}

class _LoginPageState extends State<LoginPage> {
  final emailController = TextEditingController();
  final passwordController = TextEditingController();

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      context.read<SettingsViewModel>().load();
    });
  }

  @override
  void dispose() {
    emailController.dispose();
    passwordController.dispose();
    super.dispose();
  }

  Future<void> handleLogin() async {
    final auth = context.read<AuthViewModel>();
    final success = await auth.login(emailController.text.trim(), passwordController.text.trim());
    if (!mounted) return;
    if (success) {
      Navigator.of(context).pushReplacement(MaterialPageRoute(builder: (_) => const HomePage()));
    } else {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Login failed')),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Container(
        decoration: const BoxDecoration(gradient: AppColors.authGradient),
        child: SafeArea(
          child: Center(
            child: SingleChildScrollView(
              padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 32),
              child: Container(
                padding: const EdgeInsets.all(24),
                decoration: BoxDecoration(
                  color: Colors.white.withAlpha(51),
                  borderRadius: BorderRadius.circular(24),
                  border: Border.all(color: Colors.white.withAlpha(89)),
                  boxShadow: [
                    BoxShadow(
                      color: Colors.black.withAlpha(46),
                      blurRadius: 24,
                      offset: const Offset(0, 12),
                    ),
                  ],
                ),
                child: Column(
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    const Text(
                      'Welcome to CRM',
                      style: TextStyle(fontSize: 24, fontWeight: FontWeight.w700, color: Colors.white),
                    ),
                    const SizedBox(height: 18),
                    Consumer<SettingsViewModel>(
                      builder: (context, settings, _) {
                        if (settings.siteLogo != null && settings.siteLogo!.isNotEmpty) {
                          return Image.network(
                            settings.siteLogo!,
                            height: 80,
                            fit: BoxFit.contain,
                          );
                        }
                        return Text(
                          settings.siteTitle ?? 'CR Sales',
                          style: const TextStyle(fontSize: 22, fontWeight: FontWeight.w700, color: Colors.white),
                        );
                      },
                    ),
                    const SizedBox(height: 20),
                    AppInput(
                      label: 'Email',
                      controller: emailController,
                      keyboardType: TextInputType.emailAddress,
                    ),
                    const SizedBox(height: 12),
                    AppInput(
                      label: 'Password',
                      controller: passwordController,
                      obscureText: true,
                    ),
                    const SizedBox(height: 20),
                    Consumer<AuthViewModel>(
                      builder: (context, auth, _) {
                        return AppButton(
                          label: 'Login',
                          onPressed: handleLogin,
                          isLoading: auth.isBusy,
                          backgroundColor: AppColors.authButton,
                        );
                      },
                    ),
                  ],
                ),
              ),
            ),
          ),
        ),
      ),
    );
  }
}

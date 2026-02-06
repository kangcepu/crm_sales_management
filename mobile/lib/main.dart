import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'core/api_config.dart';
import 'core/app_theme.dart';
import 'services/api_client.dart';
import 'services/location_service.dart';
import 'services/report_service.dart';
import 'services/settings_service.dart';
import 'services/store_service.dart';
import 'services/visit_service.dart';
import 'viewmodels/auth_viewmodel.dart';
import 'viewmodels/report_viewmodel.dart';
import 'viewmodels/settings_viewmodel.dart';
import 'viewmodels/store_input_viewmodel.dart';
import 'views/home_page.dart';
import 'views/login_page.dart';

Future<void> main() async {
  WidgetsFlutterBinding.ensureInitialized();
  final client = ApiClient(ApiConfig.baseUrl);
  final authViewModel = AuthViewModel(client);
  await authViewModel.loadSession();

  runApp(CrSalesApp(client: client, authViewModel: authViewModel));
}

class CrSalesApp extends StatelessWidget {
  const CrSalesApp({super.key, required this.client, required this.authViewModel});

  final ApiClient client;
  final AuthViewModel authViewModel;

  @override
  Widget build(BuildContext context) {
    return MultiProvider(
      providers: [
        ChangeNotifierProvider<AuthViewModel>.value(value: authViewModel),
        ChangeNotifierProvider(
          create: (_) => SettingsViewModel(SettingsService(client)),
        ),
        ChangeNotifierProvider(
          create: (_) => StoreInputViewModel(StoreService(client), VisitService(client), LocationService()),
        ),
        ChangeNotifierProvider(
          create: (_) => ReportViewModel(StoreService(client), VisitService(client), ReportService(client), LocationService()),
        ),
      ],
      child: Consumer<AuthViewModel>(
        builder: (context, auth, _) {
          return MaterialApp(
            debugShowCheckedModeBanner: false,
            title: 'CR Sales',
            theme: AppTheme.light(),
            home: auth.isAuthenticated ? const HomePage() : const LoginPage(),
          );
        },
      ),
    );
  }
}

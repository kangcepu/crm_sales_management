import 'dart:io';
import 'package:geolocator/geolocator.dart';
import '../core/api_config.dart';

class LocationService {
  LocationSettings _settings() {
    final base = LocationSettings(
      accuracy: LocationAccuracy.best,
      timeLimit: Duration(seconds: ApiConfig.locationTimeoutSeconds),
    );
    if (Platform.isAndroid) {
      return AndroidSettings(
        accuracy: LocationAccuracy.best,
        timeLimit: Duration(seconds: ApiConfig.locationTimeoutSeconds),
        forceLocationManager: true,
      );
    }
    if (Platform.isIOS) {
      return AppleSettings(
        accuracy: LocationAccuracy.best,
        timeLimit: Duration(seconds: ApiConfig.locationTimeoutSeconds),
      );
    }
    return base;
  }

  Future<Position?> getCurrentPosition() async {
    final serviceEnabled = await Geolocator.isLocationServiceEnabled();
    if (!serviceEnabled) {
      return null;
    }

    var permission = await Geolocator.checkPermission();
    if (permission == LocationPermission.denied) {
      permission = await Geolocator.requestPermission();
    }
    if (permission == LocationPermission.deniedForever || permission == LocationPermission.denied) {
      return null;
    }

    try {
      return await Geolocator.getCurrentPosition(locationSettings: _settings());
    } catch (_) {
      return null;
    }
  }

  Future<LocationValidationResult> getVerifiedPosition() async {
    final serviceEnabled = await Geolocator.isLocationServiceEnabled();
    if (!serviceEnabled) {
      return const LocationValidationResult(error: 'Location service disabled');
    }

    var permission = await Geolocator.checkPermission();
    if (permission == LocationPermission.denied) {
      permission = await Geolocator.requestPermission();
    }
    if (permission == LocationPermission.deniedForever || permission == LocationPermission.denied) {
      return const LocationValidationResult(error: 'Location permission denied');
    }

    try {
      final position = await Geolocator.getCurrentPosition(locationSettings: _settings());
      if (position.isMocked) {
        return const LocationValidationResult(error: 'Fake GPS detected');
      }
      if (position.accuracy > ApiConfig.maxLocationAccuracyMeters) {
        return LocationValidationResult(
          error: 'Accuracy too low (${position.accuracy.toStringAsFixed(1)} m)',
        );
      }
      return LocationValidationResult(position: position);
    } catch (_) {
      return const LocationValidationResult(error: 'Unable to read location');
    }
  }

  double distanceBetween(double startLat, double startLng, double endLat, double endLng) {
    return Geolocator.distanceBetween(startLat, startLng, endLat, endLng);
  }
}

class LocationValidationResult {
  const LocationValidationResult({this.position, this.error});

  final Position? position;
  final String? error;

  bool get isValid => position != null && error == null;
}

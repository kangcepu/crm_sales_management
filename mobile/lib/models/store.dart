import 'store_address.dart';

class Store {
  final int id;
  final String storeCode;
  final String storeName;
  final String storeType;
  final String ownerName;
  final String phone;
  final bool isActive;
  final String? erpStoreId;
  final StoreAddress? address;

  Store({
    required this.id,
    required this.storeCode,
    required this.storeName,
    required this.storeType,
    required this.ownerName,
    required this.phone,
    required this.isActive,
    this.erpStoreId,
    this.address,
  });

  factory Store.fromJson(Map<String, dynamic> json) {
    return Store(
      id: json['id'] as int,
      storeCode: (json['store_code'] ?? '').toString(),
      storeName: (json['store_name'] ?? '').toString(),
      storeType: (json['store_type'] ?? '').toString(),
      ownerName: (json['owner_name'] ?? '').toString(),
      phone: (json['phone'] ?? '').toString(),
      isActive: (json['is_active'] ?? true) == true,
      erpStoreId: json['erp_store_id']?.toString(),
      address: json['address'] is Map<String, dynamic>
          ? StoreAddress.fromJson(json['address'] as Map<String, dynamic>)
          : null,
    );
  }
}

<?php

namespace App\Enums;

enum AppRole: string {
  case Superadmin = 'superadmin';
  case Admin = 'admin';
  case TataUsaha = 'tata_usaha';
  case Guru = 'guru';
  case WaliKelas = 'wali_kelas';
  case GuruPiket = 'guru_piket';
  case GuruBk = 'guru_bk';
  case Kesiswaan = 'kesiswaan';
  case Siswa = 'siswa';

  public function label(): string {
    return match ($this) {
      self::Superadmin => 'Superadmin',
      self::Admin => 'Admin',
      self::TataUsaha => 'Tata Usaha',
      self::Guru => 'Guru',
      self::WaliKelas => 'Wali Kelas',
      self::GuruPiket => 'Guru Piket',
      self::GuruBk => 'Guru BK',
      self::Kesiswaan => 'Kesiswaan',
      self::Siswa => 'Siswa',
    };
  }

  public static function values(): array {
    return array_column(self::cases(), 'value');
  }
}

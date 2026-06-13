{pkgs}: {
  deps = [
    pkgs.php82Extensions.mysqli
    pkgs.php82Extensions.pdo_mysql
    pkgs.mysql80
    pkgs.unzip
  ];
}

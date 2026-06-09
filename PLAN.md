# Laravel LaunchKit - PLAN

## 1. Ana Kural

Bu proje önce planlanacak, sonra kodlanacaktır.

Kodlama, paket kurulumu, Laravel iskeleti oluşturma, migration yazma, Blade dosyası oluşturma veya frontend build adımlarına bu `PLAN.md` tamamlandıktan sonra geçilecektir.

Kodlama sırasında bu dosyadaki sıra takip edilecektir.

## 2. Proje Özeti

**Proje adı:** Laravel LaunchKit

**GitHub proje adı:** Laravel LaunchKit

**GitHub repository slug:** `laravel-launchkit`

**Klasör adı:** `laravel-launchkit`

**Amaç:** Laravel geliştiricileri için yeni projelerde hızlı başlangıç sağlayan, modern, temiz, açık kaynak kalitesinde ve GitHub'da paylaşılabilir bir starter kit oluşturmak.

Laravel LaunchKit; auth, profil, rol ve izin, ayarlar, activity log, dosya yöneticisi, dashboard, dark mode ve responsive arayüz gibi sık kullanılan ihtiyaçları sade bir yapıda hazır sunacaktır.

## 2.1 Hedef Kullanıcı Kitlesi

- Yeni Laravel projesine hızlı başlamak isteyen freelance geliştiriciler.
- GitHub üzerinde temiz ve anlaşılır starter kit arayan backend geliştiriciler.
- Dashboard, auth, rol ve ayar yapısını sıfırdan tekrar yazmak istemeyen küçük ekipler.
- Laravel, TailwindCSS ve Alpine.js ile sade full-stack proje örneği incelemek isteyen öğrenciler.

## 2.2 Problem ve Çözüm Açıklaması

Problem:

- Yeni Laravel projelerinde aynı temel modüller tekrar tekrar kurulmaktadır.
- Auth, profil, kullanıcı yönetimi, rol/izin, ayarlar ve activity log gibi parçalar çoğu projede başlangıç maliyeti oluşturur.
- Hazır admin panelleri bazen gereğinden ağır, kapalı veya fazla soyut olabilir.

Çözüm:

- Laravel LaunchKit bu ortak ihtiyaçları sade, okunabilir ve değiştirilebilir bir starter kit halinde sunar.
- Kod tabanı Laravel standartlarına yakın tutulur.
- Her modül tek başına anlaşılabilir olacak şekilde controller, model, migration, seeder ve Blade ekranlarına ayrılır.
- Docker desteğiyle lokal geliştirme ortamı daha hızlı ayağa kaldırılır.

## 2.3 Kapsam İçi Özellikler

- Landing page
- Auth ekranları
- Dashboard
- Profil ve avatar yönetimi
- Şifre değiştirme
- Kullanıcı yönetimi
- Rol ve izin yönetimi
- Ayarlar yönetimi
- Activity log listesi
- Basit dosya yöneticisi
- Dark mode
- Responsive sidebar
- Notification dropdown
- Demo kullanıcı seedleri
- İngilizce ve Türkçe README
- Dockerfile, docker-compose.yml ve .dockerignore

## 2.4 Kapsam Dışı Bırakılan Özellikler

- Gelişmiş admin panel paketi entegrasyonu
- Çok kiracılı mimari
- Ücretli abonelik sistemi
- API token yönetimi
- WebSocket bildirimleri
- Dosya önizleme ve medya kütüphanesi
- Üretim ortamı için gelişmiş Docker orchestration
- Kubernetes veya cloud deployment tanımları

## 3. Güncel Sürüm ve Teknoloji Kararı

Resmi Laravel dokümantasyonuna göre Laravel 13.x güncel ana sürümdür ve minimum PHP 8.3 gerektirir. Bu makinede PHP 8.4 bulunduğu için proje Laravel 13.x üzerine kurulacaktır.

Kullanılacak temel stack:

- Laravel 13.x
- PHP 8.4 uyumlu yapı
- SQLite varsayılan geliştirme veritabanı
- Vite
- TailwindCSS
- Alpine.js
- Blade
- Laravel'in kendi auth altyapısı
- Spatie Laravel Permission
- Spatie Laravel Activitylog
- Laravel Pint

Kullanılmayacaklar:

- Filament
- Go
- Python
- Gereksiz ağır admin panel mimarisi
- Deprecated starter paketler

## 4. Frontend Yaklaşımı

Frontend tarafında sade, Laravel'e doğal uyumlu ve açık kaynak starter kit için kolay anlaşılır bir yapı tercih edilecektir.

Seçim:

- Blade + TailwindCSS + Alpine.js

Gerekçe:

- Laravel geliştiricileri için öğrenmesi ve değiştirmesi kolaydır.
- Livewire'a göre daha az paket ve daha az soyutlama gerektirir.
- Starter kit amacı için yeterince güçlüdür.
- Dashboard, dropdown, sidebar, dark mode ve küçük etkileşimler Alpine.js ile sade şekilde çözülebilir.

## 5. Mimari

Proje Laravel'in standart dizin yapısına sadık kalacaktır.

Planlanan ana katmanlar:

- Routes
  - `routes/web.php`
  - auth route grupları
  - admin/management route grupları
- Controllers
  - Landing controller gerekirse sade closure
  - DashboardController
  - ProfileController
  - SettingsController
  - UserController
  - RoleController
  - ActivityLogController
  - FileManagerController
- Requests
  - ProfileUpdateRequest
  - PasswordUpdateRequest
  - AvatarUploadRequest
  - UserStoreRequest
  - UserUpdateRequest
  - RoleStoreRequest
  - RoleUpdateRequest
  - SettingUpdateRequest
  - FileUploadRequest
- Models
  - User
  - Setting
  - ManagedFile
  - Spatie modelleri: Role, Permission
  - Spatie activity log modeli
- Policies
  - UserPolicy
  - RolePolicy
  - ManagedFilePolicy
  - ActivityLogPolicy
- Seeders
  - RoleAndPermissionSeeder
  - DemoUserSeeder
  - SettingSeeder
  - DatabaseSeeder
- Views
  - layouts
  - components
  - auth
  - dashboard
  - profile
  - settings
  - users
  - roles
  - activity-logs
  - file-manager
  - landing

## 6. Modüller

### 6.1 Landing Page

Amaç:

- Projeyi tanıtan profesyonel bir ilk ekran sağlamak.

İçerik:

- Proje adı
- Kısa açıklama
- Özellik listesi
- GitHub odaklı açık kaynak vurgusu
- Login ve Register bağlantıları
- Responsive tasarım
- Dark mode uyumu

### 6.2 Auth Sistemi

Özellikler:

- Register
- Login
- Logout
- Password reset altyapısına uygun yapı
- Auth middleware
- Guest middleware
- Demo kullanıcılarıyla hızlı test

Yaklaşım:

- Laravel'in güncel auth scaffold yaklaşımı incelenecek.
- Gerekirse minimal Blade tabanlı auth ekranları proje içinde oluşturulacak.
- Deprecated Laravel Breeze kullanılmayacak.

### 6.3 Dashboard

Özellikler:

- İstatistik kartları
- Toplam kullanıcı
- Toplam rol
- Toplam dosya
- Son aktiviteler
- Son yüklenen dosyalar
- Responsive dashboard layout

### 6.4 Profil

Özellikler:

- Ad güncelleme
- E-posta güncelleme
- Avatar yükleme
- Avatar silme
- Şifre değiştirme bağlantısı veya bölümü

### 6.5 Şifre Değiştirme

Özellikler:

- Mevcut şifre kontrolü
- Yeni şifre
- Yeni şifre tekrar
- Laravel validation
- Güvenli hashleme

### 6.6 Role & Permission

Özellikler:

- Rol listesi
- Rol oluşturma
- Rol düzenleme
- Rol silme
- Permission atama
- Kullanıcıya rol atama

Planlanan roller:

- super-admin
- admin
- user

Planlanan permissionlar:

- view dashboard
- manage users
- manage roles
- view activity logs
- manage settings
- manage files

### 6.7 Ayarlar Sayfası

Özellikler:

- Site adı
- Site açıklaması
- Varsayılan tema tercihi
- Kayıt açık/kapalı ayarı
- Dosya yükleme maksimum boyut ayarı

### 6.8 Dark Mode

Özellikler:

- Light/dark tema geçişi
- LocalStorage ile tercih saklama
- Tailwind `dark` class stratejisi
- Dashboard ve auth ekranlarında uyumlu renkler

### 6.9 Responsive Sidebar

Özellikler:

- Desktop sidebar
- Mobile drawer/sidebar
- Aktif menü durumu
- Kullanıcı bilgisi alanı
- Logout kısayolu

### 6.10 Notification Dropdown

Özellikler:

- Üst bar içinde notification butonu
- Demo bildirim listesi
- Son activity log kayıtlarından özet gösterebilme
- Alpine.js dropdown

### 6.11 Activity Log

Özellikler:

- Kullanıcı girişi, profil güncelleme, dosya yükleme, rol değişikliği gibi olayları kaydetme
- Listeleme
- Filtreleme için temel hazırlık
- Detay alanı

### 6.12 Basit Dosya Yöneticisi

Özellikler:

- Dosya yükleme
- Dosya listesi
- Dosya indirme
- Dosya silme
- Dosya adı, mime type, boyut ve yükleyen kullanıcı bilgisi
- Storage disk kullanımı

### 6.13 Demo Seedleri

Demo kullanıcılar:

- Super Admin: `superadmin@example.com` / `password`
- Admin: `admin@example.com` / `password`
- User: `user@example.com` / `password`

Seed içerikleri:

- Roller
- Permissionlar
- Demo kullanıcılar
- Varsayılan ayarlar
- Demo activity log kayıtları

## 7. Sayfalar

Oluşturulacak sayfalar:

- Landing page
- Login
- Register
- Dashboard
- Profile
- Settings
- Users
- Roles
- Activity logs
- File manager

## 8. Veritabanı Tabloları

Laravel varsayılan tabloları:

- `users`
- `password_reset_tokens`
- `sessions`
- `cache`
- `jobs`

Güncellenecek `users` alanları:

- `id`
- `name`
- `email`
- `email_verified_at`
- `password`
- `avatar_path`
- `remember_token`
- `created_at`
- `updated_at`

Yeni tablolar:

### 8.1 `settings`

- `id`
- `key`
- `value`
- `type`
- `created_at`
- `updated_at`

### 8.2 `managed_files`

- `id`
- `user_id`
- `original_name`
- `stored_name`
- `path`
- `disk`
- `mime_type`
- `size`
- `created_at`
- `updated_at`

Paket tabloları:

### 8.3 Spatie Permission tabloları

- `roles`
- `permissions`
- `model_has_roles`
- `model_has_permissions`
- `role_has_permissions`

### 8.4 Spatie Activitylog tabloları

- `activity_log`

## 8.5 İlişki Şeması Açıklaması

- `users` tablosu uygulamadaki ana kullanıcı kaynağıdır.
- `managed_files.user_id` alanı `users.id` alanına bağlıdır; bir kullanıcı birden fazla dosya yükleyebilir.
- Spatie Permission tabloları kullanıcı, rol ve permission ilişkilerini pivot tablolarla yönetir.
- `activity_log.causer_id` genellikle işlemi yapan kullanıcıyı temsil eder.
- `settings` tablosu bağımsız anahtar-değer yapısındadır ve global uygulama ayarlarını tutar.

## 8.6 Route/API Planı

Bu starter kit ilk sürümde web tabanlı Blade ekranları sunar; ayrı API katmanı kapsam dışıdır.

- `GET /` landing page
- `GET /login` login ekranı
- `POST /login` login işlemi
- `GET /register` kayıt ekranı
- `POST /register` kayıt işlemi
- `POST /logout` çıkış işlemi
- `GET /dashboard` dashboard
- `GET /profile` profil ekranı
- `PUT /profile` profil güncelleme
- `PUT /profile/password` şifre değiştirme
- `GET /settings` ayarlar ekranı
- `PUT /settings` ayar kaydetme
- `GET /users` kullanıcı listesi
- `POST /users` kullanıcı oluşturma
- `PUT /users/{user}/role` kullanıcı rolü güncelleme
- `GET /roles` rol listesi
- `POST /roles` rol oluşturma
- `PUT /roles/{role}` rol izinlerini güncelleme
- `GET /activity-logs` activity log listesi
- `GET /file-manager` dosya yöneticisi
- `POST /file-manager` dosya yükleme
- `GET /file-manager/{managedFile}/download` dosya indirme
- `DELETE /file-manager/{managedFile}` dosya silme

## 9. Paketler

Composer paketleri:

- `laravel/framework`
- `spatie/laravel-permission`
- `spatie/laravel-activitylog`
- `laravel/pint`

NPM paketleri:

- `vite`
- `tailwindcss`
- `@tailwindcss/vite`
- `alpinejs`
- `axios`

Paket seçimi ilkeleri:

- Güncel stabil sürümler kullanılacak.
- Deprecated paket kullanılmayacak.
- Projeyi gereksiz karmaşıklaştıran paketlerden kaçınılacak.

## 10. Dosya ve Klasör Planı

Oluşturulacak önemli proje dosyaları:

- `README.md`
- `README_tr.md`
- `PLAN.md`
- `LICENSE`
- `docs/screenshots/landing.png`
- `docs/screenshots/dashboard.png`
- `docs/screenshots/profile.png`
- `docs/screenshots/settings.png`
- `docs/screenshots/users.png`
- `docs/screenshots/dark-mode.png`
- `Dockerfile`
- `docker-compose.yml`
- `.dockerignore`

Eğer gerçek ekran görüntüsü alınamazsa placeholder görseller oluşturulacak ve README dosyalarında "replace with real screenshot" notu yer alacaktır.

## 10.1 Component Planı

- Ana layout componenti: sidebar, topbar, notification dropdown ve tema seçiciyi içerir.
- Auth ekranları: sade form kartları.
- Dashboard kartları: tekrar kullanılabilir istatistik kartı yaklaşımı.
- Tablo ekranları: kullanıcı ve rol yönetimi için responsive tablo yapısı.
- Form alanları: TailwindCSS utility sınıflarıyla tutarlı input/select/button görünümü.
- Dark mode: tüm componentlerde `dark:` varyantlarıyla desteklenir.

## 10.2 Validasyon Kuralları

- Login: e-posta ve şifre zorunlu.
- Register: ad, benzersiz e-posta, doğrulanmış şifre.
- Profil: ad, benzersiz e-posta, opsiyonel maksimum 2 MB görsel avatar.
- Şifre: mevcut şifre doğrulaması, yeni şifre ve tekrar alanı.
- Ayarlar: site adı zorunlu, tema değeri `light`, `dark` veya `system`, yükleme boyutu limitli integer.
- Kullanıcı oluşturma: ad, benzersiz e-posta, güçlü şifre, geçerli rol.
- Rol oluşturma: benzersiz rol adı ve geçerli permission listesi.
- Dosya yükleme: maksimum 10 MB dosya.

## 10.3 Yetkilendirme ve Rol Planı

- `super-admin`: tüm permissionlara sahiptir.
- `admin`: kullanıcı, ayar, dosya ve activity log yönetebilir.
- `user`: dashboard görebilir ve dosya yöneticisini kullanabilir.
- Kritik ekranlar Spatie permission kontrolleriyle korunur.
- Yetkisiz erişimlerde HTTP 403 döner.

## 10.4 Güvenlik Notları

- `.env` dosyası Git'e eklenmez.
- Şifreler Laravel hash altyapısı ile saklanır.
- Formlarda CSRF koruması kullanılır.
- Dosya yüklemeleri Laravel validation ve storage disk üzerinden yapılır.
- Public storage için `php artisan storage:link` kullanılır.
- Rol/izin yönetimi sadece yetkili kullanıcılarla sınırlandırılır.
- Activity log kritik işlemlerde iz bırakır.

## 10.5 Docker Mimarisi

Docker desteği aşağıdaki servislerden oluşur:

- `app`: PHP 8.4 CLI tabanlı Laravel uygulama servisi.
- `node`: frontend bağımlılıkları ve Vite build işlemleri için Node 22 servisi.
- `mysql`: MySQL 8.4 geliştirme veritabanı.
- `redis`: cache/queue altyapısı için Redis servisi.
- `mailpit`: lokal mail testleri için SMTP ve web arayüzü.

Docker geliştirme hedefleri:

- Projeyi tek komutla ayağa kaldırmak.
- MySQL, Redis ve Mailpit servislerini lokal kurulum gerektirmeden sağlamak.
- Frontend build işlemini ayrı Node container ile çalıştırmak.

## 10.6 Ortam Değişkenleri Planı

Lokal varsayılan:

- `DB_CONNECTION=sqlite`
- `APP_URL=http://localhost:8000`
- `FILESYSTEM_DISK=public`

Docker varsayılan:

- `DB_CONNECTION=mysql`
- `DB_HOST=mysql`
- `DB_DATABASE=laravel_launchkit`
- `DB_USERNAME=launchkit`
- `DB_PASSWORD=secret`
- `REDIS_HOST=redis`
- `MAIL_HOST=mailpit`
- `MAIL_PORT=1025`

## 11. README Planı

### 11.1 `README.md`

Dil: İngilizce

İçerik:

- Project title
- Short English description
- Why this project exists
- Features
- Tech stack
- Installation
- Environment variables
- Migration and seed commands
- Demo user credentials
- Usage examples
- Screenshots
- Roadmap
- Contributing
- License
- Author / LinkedIn: https://www.linkedin.com/in/mmucahityilmazz/

### 11.2 `README_tr.md`

Dil: Türkçe

İçerik:

- Proje başlığı
- Türkçe kısa açıklama
- Bu proje neden var?
- Özellikler
- Kullanılan teknolojiler
- Kurulum
- Ortam değişkenleri
- Migration ve seed komutları
- Demo kullanıcı bilgileri
- Kullanım örnekleri
- Ekran görüntüleri
- Yol haritası
- Katkı sağlama
- Lisans
- Geliştirici / LinkedIn: https://www.linkedin.com/in/mmucahityilmazz/

## 12. Uygulama Sırası

Kodlama bu sırayla yapılacaktır:

1. `laravel-launchkit` klasöründe Laravel 13.x uygulama iskeletini oluştur.
2. Composer ve NPM bağımlılıklarını kur.
3. TailwindCSS, Vite ve Alpine.js yapılandırmasını tamamla.
4. `.env` ve SQLite geliştirme veritabanını hazırla.
5. Auth ekranlarını ve route yapılarını oluştur.
6. Ana layout, responsive sidebar, topbar, dark mode ve notification dropdown bileşenlerini oluştur.
7. Dashboard sayfasını ve istatistik kartlarını oluştur.
8. Profil, avatar yükleme ve şifre değiştirme özelliklerini oluştur.
9. Spatie Permission kurulumunu yap.
10. Rol, permission ve kullanıcı yönetimi sayfalarını oluştur.
11. Ayarlar tablosu, modeli ve sayfasını oluştur.
12. Spatie Activitylog kurulumunu yap.
13. Activity logs sayfasını oluştur.
14. Dosya yöneticisi migration, model, controller ve sayfasını oluştur.
15. Demo seedleri oluştur.
16. README.md ve README_tr.md dosyalarını yaz.
17. Dockerfile, docker-compose.yml ve .dockerignore dosyalarını oluştur.
18. `docs/screenshots` klasörünü ve placeholder veya gerçek görselleri oluştur.
19. Migrationları çalıştır.
20. Seedleri test et.
21. Frontend build komutunu çalıştır.
22. Laravel Pint ile kod formatını düzelt.
23. README kurulum adımlarını gerçek komutlarla karşılaştırıp doğrula.

## 12.1 Test Senaryoları

- Landing page 200 döner ve proje adını gösterir.
- Login ve register ekranları 200 döner.
- Demo super admin ile dashboard açılır.
- Profile, settings, users, roles, activity logs ve file manager sayfaları render edilir.
- Migration ve seed komutu temiz çalışır.
- Frontend production build başarılı olur.
- Pint format kontrolü temiz geçer.

## 12.2 README İçerik Planı

README.md İngilizce hazırlanır ve şunları içerir:

- Proje başlığı
- Kısa açıklama
- GitHub proje adı, slug ve repository linki
- Neden var?
- Özellikler
- Tech stack
- Lokal kurulum
- Docker ile kurulum
- Ortam değişkenleri
- Migration ve seed komutları
- Demo kullanıcı bilgileri
- Kullanım örnekleri
- Ekran görüntüleri
- Roadmap
- Contributing
- License
- Author ve LinkedIn

README_tr.md Türkçe hazırlanır ve aynı bilgilerin Türkçe karşılığını içerir.

## 12.3 Ekran Görüntüsü Planı

README içinde aşağıdaki görseller yer alır:

- `docs/screenshots/landing.png`
- `docs/screenshots/dashboard.png`
- `docs/screenshots/profile.png`
- `docs/screenshots/settings.png`
- `docs/screenshots/users.png`
- `docs/screenshots/dark-mode.png`

Gerçek ekran görüntüsü alınamadığında geçerli PNG formatında etiketli placeholder görseller kullanılır.

## 12.4 Yayına Alma Notları

- GitHub repository adı `laravel-launchkit` olmalıdır.
- Default branch üzerinde `.env`, `vendor`, `node_modules`, build çıktıları ve cache dosyaları bulunmamalıdır.
- README içindeki `git clone` komutu `https://github.com/mchtylmz/laravel-launchkit.git` adresini göstermelidir.
- İlk release için `v0.1.0` etiketi kullanılabilir.
- Placeholder görseller daha sonra gerçek ekran görüntüleriyle değiştirilmelidir.

## 12.5 Gelecek Geliştirme Fikirleri

- E-posta doğrulama
- Şifre sıfırlama e-postaları
- Kalıcı notification modeli
- Dosya önizleme
- Daha kapsamlı feature testleri
- Docker production profili
- Opsiyonel API auth katmanı

## 13. Kalite Kontrol Listesi

Tamamlanmadan önce çalıştırılacak kontroller:

- `php artisan migrate:fresh --seed`
- `npm run build`
- `./vendor/bin/pint`
- Auth ekranları manuel kontrol
- Demo kullanıcı girişleri manuel kontrol
- Dashboard sayfası manuel kontrol
- Profile ve avatar yükleme manuel kontrol
- Role ve permission akışı manuel kontrol
- Settings sayfası manuel kontrol
- Activity logs sayfası manuel kontrol
- File manager yükleme/silme manuel kontrol
- README.md kurulum komutları kontrol
- README_tr.md kurulum komutları kontrol

## 14. Açık Kaynak Kalite Notları

- Kodlar sade ve okunabilir olacak.
- Controllerlar gereksiz büyütülmeyecek.
- Tekrarlayan UI parçaları Blade component olarak ayrılacak.
- Validation Form Request sınıflarıyla yapılacak.
- Authorization policy veya permission middleware ile korunacak.
- README dosyaları profesyonel GitHub projesi standardında olacak.
- Varsayılan demo verileri gerçekçi ama güvenli olacak.
- `.env` dosyası repoya dahil edilmeyecek.
- `.env.example` güncel tutulacak.

## 15. Kaynak Notları

- Laravel 13.x resmi dokümantasyonunda Laravel 13 için minimum PHP 8.3 gereksinimi belirtilmiştir.
- Laravel resmi kurulum dokümantasyonu PHP, Composer ve Node/NPM gereksinimlerini belirtmektedir.

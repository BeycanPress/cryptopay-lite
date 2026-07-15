# CryptoPay Lite — Yapılacak Güncellemeler (Roadmap)

> Rakip analizi + wordpress.org deaktivasyon verisi (14 Tem 2026) temelli.
> Amaç: Lite'ı sitede tutturmak (retention) + premium'a itmek (funnel), premium'u kanibalize etmeden.

---

## 0. Bağlam — neden bu liste?

- **Saha küçük ve dağınık.** Non-custodial + multichain + wallet-connect nişi kalabalık değil:
  - NOWPayments 4.000+ (custodial, fee şikayeti), Blockonomics 2.000+ (BTC-only),
    Pay With MetaMask 600+ (EVM-only, 4.8★), Coinbase Commerce = terk edilmiş.
  - CryptoPay Lite: 100+ install, 4.3★. Niş boş, henüz alınmadı.
- **En yakın rakip Pay With MetaMask ücretsizde cömert:** refund, dashboard, testnet,
  custom token, çoklu adres, token indirimi — hepsi free. Karşılaştıran kullanıcı bizi eksik görüyor.
- **Kategorinin tek gerçek moat'ı = DESTEK.** Tüm rakiplerin #1 övgüsü "responsive support".
- **Evrensel şikayet = ücret sürprizi** (NOWPayments: "30 USDT gönderdim 19.12 aldım").
  Bizim %0 komisyon + no-KYC + P2P bunun panzehiri ama şu an yeterince bağırmıyoruz.

## 0.1 Deaktivasyon verisi (1.169 kayıt) — gerçek sinyal

- Ham sayı **çöp/bot ile şişkin** ("Plugin Check", "qdswd", "123456" gibi sebepler → tek seferlik spam).
- **#1 gerçek negatif: "çalışmadı / checkout'ta göremiyorum / frontend'de yok"** → onboarding/görünürlük.
- **"I will buy the premium version" tekrar tekrar geçiyor** → üst-huni niyeti sağlıklı; sorun aktivasyon/dönüşüm.
- İsimli rakip kaçışı: **DePay**.
- Kitlenin bir kısmı **gri-pazar** (no-KYC/no-account seçme sebebi) → geçici kullanıcı, churn'ü şişirir.

---

## 1. Şimdi yapılacaklar (yüksek ROI, premium'u yemez)

### 1.1 Kurulum sonrası / checkout görünürlük teşhisi  ⭐ EN YÜKSEK ÖNCELİK
- **Neden:** deaktivasyon #1 gerçek sebebi "checkout'ta göremiyorum / çalışmadı".
- **Ne:** basit bir setup wizard DEĞİL zorunluluk — asıl değer **doğrulama adımı**:
  - Cüzdan adresi girildi mi? En az 1 ağ aktif mi? Gateway WooCommerce'te enabled mı?
    Block checkout mu klasik mi tespit edilip uygun uyarı.
  - Admin'de yeşil "✅ Ödeme yöntemi checkout'ta görünür" / kırmızı "⚠️ şu eksik" rozeti.
- **Premium'u yer mi?** Hayır, tamamen UX/aktivasyon.

### 1.2 Basit kurulum sihirbazı (opsiyonel, 3 adım)
- **Neden:** Blockonomics "2 dakikada kurulum"u pazarlama argümanı yapıyor.
- **Ne:** Cüzdan adresi → ağ(lar) seç → test/doğrula. Atlanabilir olsun.
- **Not:** Halil'in itirazı haklı (config zaten 3 ayar); bu yüzden sihirbaz "zorunlu onboarding" değil,
  1.1'deki doğrulamanın kullanıcı-dostu sarmalı olarak düşünülmeli.

### 1.3 "%0 komisyon" mesajını can alıcı yerlere koy
- **Neden:** NOWPayments fee-sürprizi = pazarın en büyük acısı; bizim en güçlü diferansiyatör.
- **Ne:**
  - `readme.txt` ilk 2 paragrafına net karşılaştırma cümlesi (fee-surprise'a atıf).
  - Eklenti aktive olunca tek seferlik admin notice: "Diğerleri gönderiminin %35'ini alabiliyor. Biz %0."
  - Ayarlarda kalıcı küçük rozet.
- **Premium'u yer mi?** Hayır.

### 1.4 Zamanlı + kapılı review isteği (NPS-gate)
- **Neden:** wp.org sıralamasının #1 kaldıracı review; ama kötü puan riski var (Halil'in kaygısı).
- **Ne:**
  - Tetik: **ilk başarılı ödeme sonrası** (aktivasyonda DEĞİL).
  - İki aşama: "Nasıl gidiyor?" → memnun → wp.org review linki; memnun değil → mevcut `Feedback.php` akışı.
  - Kötü deneyim public rating'e değil, bize özel geri bildirime gider.
- **Premium'u yer mi?** Hayır.

### 1.5 Kilitli-ama-görünür premium özellikler
- **Neden:** "var ama kilitli" arzu yaratır; şu an premium özellikler Lite'ta tamamen görünmez.
- **Ne:** Bitcoin/Solana/QR sekmelerini kilit ikonu + soluk göster, tıklayınca faydası + "Premium ile aç".
- **Premium'u yer mi?** Tam tersi — premium talebini artırır.

### 1.6 Premium'dan Lite'a inecek özellikler (KARAR VERİLDİ ✅)
Halil kararı — bunlar Lite'a eklenecek (rakibin bedava verdiği "masa payı" özellikler, premium'un asıl
sürücüsü olan non-EVM ağlar + QR + converter'a dokunmaz):
- **OFAC / Sanctions taraması** (`Services/Sanctions.php` → Lite'a port).
- **İade / Refund** (`WooCommerce/Services/Refunds.php` → Lite'a port).
- **Instant / direkt ödemeler** (`instant.js`).
- **Premium'u yer mi?** Hayır — premium değeri hâlâ non-EVM ağlar + QR + converter + sınırsız ağ + ağ-başı adreste.

---

## 2. Kararlar (Halil tarafından karara bağlandı)

### 2.1 Refund → Lite'a EKLENECEK ✅
Karar verildi. Bkz. 1.6.

### 2.2 Custom token → PREMIUM'DA KALIR ❌
- **Gerekçe (Halil):** Lite'ta zaten ETH/BNB/Base var, insanlar bu ağlarda token basıyor.
  Custom token'ı Lite'a verirsek premium satmaz — bu premium'un gerçek satış sebeplerinden.
- **Durum:** ✅ Karar: premium kalır. (Static token listesi de yapılmaz — kötü UX.)

### 2.3 Arbitrum + Optimism (5 → 7 ağ) → Lite'a EKLENECEK ✅
- **Karar:** Lite'a eklenecek. Kod premium'da hazır, kopyalanır; karşılaştırma tablosunda "7 ağ" > "5 ağ"
  görünür, network add-on geliri riskte değil.

### 2.4 Netleşen diğerleri
- **QR / adrese-transfer:** ❌ Premium'da kalır — arkada özel sunucu (`qr-verifier`) çalışıyor,
  birçok kullanıcının premium tercih sebebi. **Asla Lite'a verilmez.**
- **Converter API'ler:** ❌ Gerek yok — default coinlerin hepsi zaten Lite'ta varsayılan geliyor.
- **Ağ-başı özel adres:** ❌ Premium'da kalır.
- **Oto-güncelleme:** Konu dışı — Lite zaten wp.org üzerinden güncelleniyor.
- **OFAC/Sanctions & Instant:** ✅ Lite'a eklenecek (bkz. 1.6).

---

## 3. Büyüme (kod dışı, paralel yürür)

- **Coinbase Commerce göç sayfası** (bkz. makale dokümanı) — terk edilen binlerce merchant.
- **Ekosistem-başı ücretsiz "lite" listeler** (elde kod var: EDD, GiveWP, CF7…). Her biri ayrı wp.org SEO yüzeyi.
- **readme ASO:** "accept bitcoin woocommerce", "usdt payment", "solana pay", "no KYC crypto payment".
- **Zincir merchant-adoption programları** (Base/Polygon/BNB/Solana) — bedava co-marketing.

---

## 4. Premium çizgisi — final (kanibalizasyon önleme)

**Premium'da kalır (kesin):** non-EVM ağlar (BTC/SOL/Tron/TON/Sui/XRPL), **QR/adrese-transfer (asla verilmez)**,
converter API'ler, sınırsız/özel EVM ağı, EVM custom token, ağ-başı özel adres.

**Lite'a inecek (karar verildi ✅):** OFAC/Sanctions, Refund, Instant/direkt ödeme, Arbitrum + Optimism (5 → 7 ağ).

**Konu dışı:** oto-güncelleme (Lite zaten wp.org'dan güncelleniyor).

**Prensip:** Lite'a inenler "engagement/masa payı + uyum"; premium "non-EVM genişlik + QR + converter + sınırsız ağ".
QR'ın arkasındaki özel sunucu maliyeti premium'un en savunulabilir gerekçesi — sert duvar burada.

---

## 5. Kod referansları (yarın port için — kaynak: premium `cryptopay` eklentisi)

> Kaynak repo: `cryptopay-project/wp-plugins/cryptopay/` (premium).
> Hedef repo: bu klasör — `cryptopay-addons/wp-plugins/cryptopay-wc-lite/`.
> Port ederken namespace `BeycanPress\CryptoPay` → `BeycanPress\CryptoPayLite`, ve premium gating/lisans kontrolünü kaldır.

### 5.1 OFAC / Sanctions  ✅
- `cryptopay/app/Services/Sanctions.php`  → Lite `app/Services/Sanctions.php`
- `cryptopay/src/js/sanctions.js`          → Lite `src/js/` (build'e ekle)
- Kayıt: Lite `app/Services/Initialize.php` içine servisi ekle (premium Initialize referans).

### 5.2 Refund / İade  ✅
- `cryptopay/app/WooCommerce/Services/Refunds.php` → Lite `app/WooCommerce/Services/`
- `cryptopay/app/Types/Order/RefundType.php`        → Lite `app/Types/Order/`
- `cryptopay/app/Types/Order/RefundsType.php`       → Lite `app/Types/Order/`
- `cryptopay/views/refund.php`                      → Lite `views/`
- `cryptopay/src/js/refund.js`                      → Lite `src/js/` (build → `assets/js/refund.min.js`)

### 5.3 Instant / direkt ödeme  ✅
- `cryptopay/views/instant.php`  → Lite `views/`
- `cryptopay/src/js/instant.js`  → Lite `src/js/` (build'e ekle)
- Not: instant akışı `cryptopay-ts` widget'ıyla da konuşuyor olabilir; port sırasında kontrol et.

### 5.4 Arbitrum + Optimism (5 → 7 ağ)  ✅
- `cryptopay/app/Settings/EvmChains.php` → Lite `app/Settings/EvmChains.php` içindeki
  ağ tanımlarına Arbitrum + Optimism ekle (chainId + testnet eşlemesi `$networkMatch`'e).
- İlgili: `cryptopay/app/Types/Enums/NetworkCodes.php` (premium'daki ağ kodları referans).
- Lite şu an sabit 5 ağ: Ethereum(1), Base(8453), BNB(56), Avalanche(43114), Polygon(137).

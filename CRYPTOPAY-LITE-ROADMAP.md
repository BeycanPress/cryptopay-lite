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
- ~~**OFAC / Sanctions taraması**~~ → ❌ **KARAR DEĞİŞTİ (15 Tem 2026): Lite'a alınmıyor.** Bkz. 2.5.
- ~~**İade / Refund**~~ → ❌ **KARAR DEĞİŞTİ (15 Tem 2026): mimari nedeniyle entegre edilemez.** Bkz. 2.6.
- ~~**Instant / direkt ödemeler**~~ ✅ **YAPILDI (15 Tem 2026).** Bkz. 5.3.
- **Premium'u yer mi?** Hayır — premium değeri hâlâ non-EVM ağlar + QR + converter + sınırsız ağ + ağ-başı adreste.

---

## 2. Kararlar (Halil tarafından karara bağlandı)

### 2.1 Refund → LİTE'A EKLENEMEZ ❌ (15 Tem 2026 — karar değişti)
- **Sebep: mimari.** Premium'da ağlar **PHP'den** gelir (`Helpers::getNetworks()` → `NetworksType`,
  zengin nesneler). Lite'ta ağlar **JS'te ön tanımlıdır**: PHP sadece chainId listesi (`array<int>`)
  gönderir, metadata `cryptopay-ts/src/networks/mainnets.json`'dadır (bkz. 5.4 uyarısı).
- **Neden bloklar:** Refund, ödeme widget'ını **orijinal ağ + orijinal token'a kısıtlamak** zorunda
  (müşteri USDT ödediyse iade USDT olmalı). Premium bunu `edit_networks_woocommerce_refund` →
  `NetworksType` + `$network->setCurrencies([$paymentCurrency])` ile yapar.
  - Lite'ta `edit_networks` filtresi **yok** ve `setNetworks()` `array<int>` alır → NetworksType uyumsuz.
  - Ağ kısıtlaması yine de yapılabilirdi (`[$chainId]`), ama **para birimi kısıtlaması TS değişikliği
    ister**: liste `main.ts:200`'de JSON'dan chainId'ye göre kurulur, TS'in 21 event'inin hiçbiri
    listeyi filtrelemez. Kısıtlamasız iade = yanlış token'da iade riski.
- **Karar:** Premium'da kalır. Lite'a alınmıyor.
- **Not (ileride bakılırsa):** DB engeli YOK — refund verisi yeni kolon değil, mevcut `order` JSON
  kolonunda durur (`$order->toJson()`). Engel tamamen ağ/para birimi kısıtlama mimarisi.

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
- **Instant:** ✅ Lite'a eklenecek (bkz. 1.6).
- **OFAC/Sanctions:** ❌ Lite'a alınmıyor (bkz. 2.5).

### 2.5 OFAC / Sanctions → LITE'A ALINMIYOR ❌ (15 Tem 2026)
- **Ne oldu:** Port teknik olarak tamamlandı ve çalışıyordu (Sanctions.php + sanctions.js + REST route
  + ayarlar). Sonra geri alındı — sebep kod değil, **sağlayıcı**.
- **Gerekçe:** Premium'daki tek sağlayıcı **Coinfirm** ve şirket **Lukka'ya satılmış**:
  - `coinfirm.com` → `lukka.tech` (301), `platform.coinfirm.com` → `blockchain-analytics.lukka.tech` (301)
  - Yani ayarlardaki "API key al" linki ölü. Üstelik Coinfirm kurumsal/ücretli —
    **ücretsiz Lite kullanıcısı key alamaz.**
  - Sonuç: ayar görünür ama çalışmaz → deaktivasyonun #1 sebebi olan "çalışmadı"yı besler (bkz. 0.1).
- **Durum:** Premium'da kalır. Lite'ta `Settings.php` içindeki `$proMsg` placeholder'ı korunuyor.
- **İleride tekrar açılırsa:** Coinfirm ile değil. **Chainalysis on-chain oracle** değerlendirilmeli —
  ücretsiz, API key gerektirmez ("does not require a customer relationship"), doğrudan OFAC SDN,
  `isSanctioned(address)` kontrat çağrısı, ve Lite'ın **7 EVM ağının tamamını** kapsıyor
  (Ethereum, Polygon, BNB, Avalanche, Base, Arbitrum, Optimism).

---

## 3. Büyüme (kod dışı, paralel yürür)

- **Coinbase Commerce göç sayfası** (bkz. makale dokümanı) — terk edilen binlerce merchant.
- **Ekosistem-başı ücretsiz "lite" listeler** (elde kod var: EDD, GiveWP, CF7…). Her biri ayrı wp.org SEO yüzeyi.
- **readme ASO:** "accept bitcoin woocommerce", "usdt payment", "solana pay", "no KYC crypto payment".
- **Zincir merchant-adoption programları** (Base/Polygon/BNB/Solana) — bedava co-marketing.

---

## 4. Premium çizgisi — final (kanibalizasyon önleme)

**Premium'da kalır (kesin):** non-EVM ağlar (BTC/SOL/Tron/TON/Sui/XRPL), **QR/adrese-transfer (asla verilmez)**,
converter API'ler, sınırsız/özel EVM ağı, EVM custom token, ağ-başı özel adres,
**OFAC/Sanctions** (bkz. 2.5), **Refund** (bkz. 2.1 — mimari engel).

**Lite'a indi (bölüm 5 tamamlandı ✅):** ~~Instant/direkt ödeme~~ ✅, ~~Arbitrum + Optimism~~ ✅ (5 → 7 ağ).

> **Genel ders (15 Tem 2026):** Roadmap'in "premium'da kod hazır, kopyala" varsayımı üç maddede de
> yanlış çıktı. Lite ile premium **aynı kod tabanı değil**; ayrışma noktası şu: **premium ağları
> PHP'den yönetir, Lite ağları JS'te ön tanımlıdır.** Bir premium özelliği ağ/para birimi listesini
> *yönlendirmeye* çalışıyorsa (Refund) Lite'a girmez; sadece olaylara *tepki veriyorsa* (Sanctions)
> girer. Port kararı vermeden önce özelliğin bu iki sınıftan hangisinde olduğuna bakılmalı.

**Konu dışı:** oto-güncelleme (Lite zaten wp.org'dan güncelleniyor).

**Prensip:** Lite'a inenler "engagement/masa payı + uyum"; premium "non-EVM genişlik + QR + converter + sınırsız ağ".
QR'ın arkasındaki özel sunucu maliyeti premium'un en savunulabilir gerekçesi — sert duvar burada.

---

## 5. Kod referansları (yarın port için — kaynak: premium `cryptopay` eklentisi)

> Kaynak repo: `cryptopay-project/wp-plugins/cryptopay/` (premium).
> Hedef repo: bu klasör — `cryptopay-addons/wp-plugins/cryptopay-wc-lite/`.
> Port ederken namespace `BeycanPress\CryptoPay` → `BeycanPress\CryptoPayLite`, ve premium gating/lisans kontrolünü kaldır.

> **JS build notu (port ederken kritik):** Lite'ta `sanctions.js` / `instant.js` / `refund.js` gibi
> dosyalar ayrı ayrı enqueue EDİLMEZ. `gulpfile.mjs`'te bunlar için task yoktur; `gulp-include`
> kullanılır ve dosya `src/js/main.js` içine `//=include ./dosya.js` satırıyla gömülür
> (premium `src/js/main.js` referans). Sonra `npx gulp js-main` → `assets/js/main.min.js`.
> Lite'ta `node_modules` yoksa önce `npm install`. Not: gulpfile modül seviyesinde `gulp.watch()`
> çağırdığı için task bitince süreç kendiliğinden kapanmaz, elle durdurmak gerekir.

### 5.1 OFAC / Sanctions  ❌ İPTAL — bkz. 2.5
Port yapıldı, sağlayıcı (Coinfirm → Lukka) ölü olduğu için geri alındı. Tekrar açılırsa Chainalysis oracle.

### 5.2 Refund / İade  ❌ İPTAL — bkz. 2.1 (mimari engel)
Bu madde "5 dosya kopyala" diye yazılmıştı; gerçek kapsam iki repo'da ~13 kalemdi ve para birimi
kısıtlaması için `cryptopay-ts` değişikliği gerekiyordu. Ek olarak Lite'ta eksik olanlar (kayıt için):
`OrderType`'ta refund desteği yok, `TransactionStatus`'ta `FULLY_REFUNDED`/`PARTIALLY_REFUNDED` yok,
`AbstractTransaction`'da `addOrderRefundData`/`deleteOrderRefundData` yok, `Payment.php`'de
`edit_networks` filtresi yok, `Helpers`'ta `getWalletsByCode` yok, gulpfile'da `js-refund` task'ı yok.

### 5.3 Instant / direkt ödeme  ✅ YAPILDI (15 Tem 2026)
> "cryptopay-ts ile konuşuyor olabilir, kontrol et" notu haklıydı — **konuşuyor, ama sadece sürüyor:**
> `start()` / `reStart()` / `modal.open()` / `cplHelpers.errorPopup()`. Ağ veya para birimi listesini
> **yönlendirmiyor**, o yüzden Refund'un aksine TS'e dokunmadan port edildi (bkz. 4'teki genel ders).
> Doğrulandı: `main.ts:272` `start()` → `{store, reStart}`, `main.ts:280` `modal.open()`.

Yapılanlar:
- `views/instant.php` → Lite `views/` (aynen)
- `src/js/instant.js` → Lite `src/js/` (`CryptoPayVars`→`CryptoPayLiteVars`, `CryptoPayApp`→`CryptoPayLiteApp`,
  `cpHelpers`→`cplHelpers`, `CryptoPayLang`→`CryptoPayLiteLang`)
- `src/js/main.js` → `//=include ./instant.js` (ayrı enqueue YOK, bkz. yukarıdaki JS build notu)
- `app/WooCommerce/Services/Payment.php` → 4 ekleme:
  `woocommerce_after_add_to_cart_form` action'ı, `instant()` metodu,
  `init()`'e instant dalı, `beforePaymentStarted()`'a instant sipariş oluşturma dalı
- `app/Settings/Settings.php` → `acceptInstantPayments` artık `$proMsg` placeholder değil, gerçek switcher
- `assets/js/main.min.js` → `npx gulp js-main` ile build edildi

Dikkat edilenler:
- **`set_payment_method`**: premium `'cryptopay'` yazar; Lite'ta gateway ID **`cryptopay_lite`**'tır
  (tire değil alt çizgi). Literal yerine `Gateway::ID` sabiti kullanıldı.
- **Sıra kritik**: `Hook::addFilter('js_variables', ...)` mutlaka `->modal()` çağrısından ÖNCE olmalı,
  yoksa `CryptoPayLiteVars.instant` JS'e hiç ulaşmaz.
- `instantPaymentMsg` lang string'i Lite `Constants.php`'de zaten vardı (fork kalıntısı), eklenmedi.

### 5.4 Arbitrum + Optimism (5 → 7 ağ)  ✅ YAPILDI (15 Tem 2026)
> ⚠️ **Bu madde başta yanlış tarif edilmişti:** "Lite `EvmChains.php`'ye ağ ekle" denmişti, sanki tek
> repo'da PHP işiymiş gibi. **Değil.** Lite'ın ağ metadata'sı (isim/rpcUrl/ikon/token) bu repo'da
> DEĞİL, **`cryptopay-project/cryptopay-ts` (branch: `lite`)** içindedir. Lite PHP sadece chainId
> listesi gönderir; `main.ts` bu listeye göre `networks/mainnets.json`'u filtreler. Sadece PHP'ye
> eklersen ağ JS filtresinden düşer ve **checkout'ta hiç görünmez.**

Yapılanlar:
- `cryptopay-ts` (lite) `src/networks/mainnets.json` → Arbitrum One (42161), Optimism (10)
- `cryptopay-ts` (lite) `src/networks/testnets.json` → Arbitrum Sepolia (421614), OP Sepolia (11155420)
- Lite `app/Settings/EvmChains.php` → `id_42161` + `id_10` switcher, `$networkMatch` eşlemesi
- Lite `assets/images/icons/` → `arb.svg`, `op.svg` (premium'dan kopyalandı; `image` alanı bu adı referans verir)
- `cryptopay-ts` içinde `npm run build` → vite `outDir` zaten Lite `assets/js`'e yazıyor (`app.min.js`)
- Lite'ta `NetworkCodes` enum'u YOK; ağ kodu düz `'evmchains'` string'i (mainnets.json'daki `code` ile eşleşir).

Token adresleri (para taşıdığı için kaynak kaydı):
- USDC → Circle resmi dokümanı. Testnet değerleri premium `getTestnetsCurrencies()` ile birebir eşleşti.
- USDT → Halil verdi (Arb `0xFd086...FCbb9`, OP `0x94b008...58e58`). Tether resmi sayfası bu iki L2'yi
  listelemiyor (köprülenmiş token).
- ARB / OP → premium'un canlı config'inden.
- **DAI eklenmedi** — yetkili kaynaktan doğrulanamadı. Diğer 5 ağda DAI var, bu ikisinde ARB/OP duruyor.

Mevcut kurulum notu: `evmchainsNetworks` DB'de kayıtlı olduğu için yeni 2 switcher `default => true`
olmasına rağmen **eski kullanıcılarda kapalı gelir**, sadece yeni kurulumlarda açık. "7 ağ" argümanı
eski kullanıcılarda görünmez — istenirse migration gerekir.

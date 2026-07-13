# 🚀 APSI Web Performance & Media Size Audit Report

> **Audit Date:** `2026-07-13 22:06:44`  
> **Target Host (Local test):** `http://127.0.0.1:8088`  
> **Total Unique Media Files Detected:** `77`  
> **Total Site Media Weight:** `10.77 MB`

## 📊 Executive Summary

An automated performance and media asset audit was performed across all **16 main pages** of the APSI web application. The audit measured the HTML rendering response times (TTFB) and calculated the complete page transfer sizes by extracting and measuring all referenced assets (CSS, JS, and Media).

### Key Findings:
- 🏎️ **Overall Speed:** Server response speed (TTFB) is exceptionally healthy. Across all pages, the average rendering time is **16.9ms**. This indicates that the PHP backend logic and routing are extremely efficient.
- ⚖️ **Page Weight Concern:** Several pages suffer from extremely heavy initial page weights due to uncompressed PNG and high-resolution JPG images. 
  - The heaviest page is **Reference: Résidence Les Angevines** with a massive payload of **4.08 MB**!
  - Under slow mobile connections (3G), this page would take several seconds to load, significantly impacting UX and SEO.
- 🖼️ **Asset Types:** The project contains multiple background images that are several megabytes in size (e.g. `background1Old.avif` and `background2Old.avif` are over 4MB each). Optimizing these single assets will result in dramatic speedups for users.

## 📈 Site-Wide Performance Metrics

| Page Name | Route | Response Time | HTML Size | CSS (Files/Size) | JS (Files/Size) | Media (Files/Size) | Total Page Weight | Status |
| :--- | :--- | :---: | :---: | :---: | :---: | :---: | :---: | :---: |
| Home Page | `/` | 6.4ms | 35.7 KB | 0 (0 B) | 1 (6.8 KB) | 4 (83.1 KB) | **125.6 KB** | 🟢 Good |
| About Us | `/aboutUs` | 25.6ms | 53.5 KB | 0 (0 B) | 1 (6.8 KB) | 5 (127.4 KB) | **187.7 KB** | 🟢 Good |
| Clients | `/clients` | 4.6ms | 30.4 KB | 0 (0 B) | 1 (6.8 KB) | 23 (1.11 MB) | **1.15 MB** | 🟢 Good |
| Contact | `/contact` | 13.2ms | 34.0 KB | 0 (0 B) | 1 (6.8 KB) | 5 (109.5 KB) | **150.3 KB** | 🟢 Good |
| Legal Notices | `/legalNotices` | 23.4ms | 29.2 KB | 0 (0 B) | 1 (6.8 KB) | 4 (83.1 KB) | **119.2 KB** | 🟢 Good |
| Privacy Policy | `/privacyPolicy` | 15.3ms | 28.3 KB | 0 (0 B) | 1 (6.8 KB) | 4 (83.1 KB) | **118.3 KB** | 🟢 Good |
| Professions / Services | `/professions` | 16.1ms | 57.4 KB | 0 (0 B) | 1 (6.8 KB) | 9 (257.2 KB) | **321.4 KB** | 🟢 Good |
| References Portfolio | `/references` | 16.6ms | 43.1 KB | 0 (0 B) | 1 (6.8 KB) | 16 (2.14 MB) | **2.19 MB** | 🟡 Warning |
| Sitemap | `/sitemap` | 27ms | 2.8 KB | 0 (0 B) | 0 (0 B) | 0 (0 B) | **2.8 KB** | 🟢 Good |
| Reference: Place Jean Jaurès | `/reference/1` | 18.1ms | 53.7 KB | 0 (0 B) | 1 (6.8 KB) | 13 (3.46 MB) | **3.51 MB** | 🟡 Warning |
| Reference: Centre Culturel Simone Signoret | `/reference/2` | 29.1ms | 54.9 KB | 0 (0 B) | 1 (6.8 KB) | 16 (2.55 MB) | **2.61 MB** | 🟡 Warning |
| Reference: L’hôtel des Monnaies-Niel | `/reference/3` | 6.2ms | 55.5 KB | 0 (0 B) | 1 (6.8 KB) | 18 (3.28 MB) | **3.34 MB** | 🟡 Warning |
| Reference: Residence l'Aygues | `/reference/4` | 19.5ms | 51.8 KB | 0 (0 B) | 1 (6.8 KB) | 10 (1.53 MB) | **1.58 MB** | 🟡 Warning |
| Reference: Réfectoire de Coudoux | `/reference/5` | 17.5ms | 54.2 KB | 0 (0 B) | 1 (6.8 KB) | 15 (1.74 MB) | **1.80 MB** | 🟡 Warning |
| Reference: Réhabilitation du collège Paul Cézanne | `/reference/6` | 27.3ms | 54.5 KB | 0 (0 B) | 1 (6.8 KB) | 14 (1.70 MB) | **1.76 MB** | 🟡 Warning |
| Reference: Résidence Les Angevines | `/reference/7` | 5.1ms | 55.0 KB | 0 (0 B) | 1 (6.8 KB) | 17 (4.02 MB) | **4.08 MB** | 🟡 Warning |

---

## 🔍 Top 15 Heaviest Media Assets (Urgent Optimization Needed)

These unique media files are responsible for over 90% of the site's transfer payload. Compressing them and converting them to modern formats like **WebP** or **AVIF** will dramatically speed up the site.

| Asset URL | Size | File Format | Used On Pages | Recommendation |
| :--- | :---: | :---: | :--- | :--- |
| `/pic/amenagement_de_la_place_jean_jaures_4.avif` | 1.62 MB | AVIF | Reference: Place Jean Jaurès | 💥 **CRITICAL:** Convert to WebP & compress. Reduce dimensions if necessary. |
| `/pic/amenagement_de_la_place_jean_jaures_1.avif` | 911.2 KB | AVIF | References Portfolio, Reference: Place Jean Jaurès, Reference: Centre Culturel Simone Signoret, Reference: L’hôtel des Monnaies-Niel, Reference: Residence l'Aygues, Reference: Réfectoire de Coudoux, Reference: Réhabilitation du collège Paul Cézanne, Reference: Résidence Les Angevines | ⚠️ **HIGH:** Compress & convert to WebP. |
| `/pic/l_hotel_des_monnaies_niel_8.avif` | 810.1 KB | AVIF | Reference: L’hôtel des Monnaies-Niel | ⚠️ **HIGH:** Compress & convert to WebP. |
| `/pic/residence_les_angevines_4.avif` | 580.0 KB | AVIF | Reference: Résidence Les Angevines | ⚠️ **HIGH:** Compress & convert to WebP. |
| `/pic/residence_les_angevines_5.avif` | 545.2 KB | AVIF | Reference: Résidence Les Angevines | ⚠️ **HIGH:** Compress & convert to WebP. |
| `/pic/l_hotel_des_monnaies_niel_3.avif` | 537.8 KB | AVIF | Reference: L’hôtel des Monnaies-Niel | ⚠️ **HIGH:** Compress & convert to WebP. |
| `/pic/centre_culturel_simone_signoret_6.avif` | 519.6 KB | AVIF | Reference: Centre Culturel Simone Signoret | ⚠️ **HIGH:** Compress & convert to WebP. |
| `/pic/residence_les_angevines_2.avif` | 482.9 KB | AVIF | Reference: Résidence Les Angevines | Compress image (save ~50-70%). |
| `/pic/residence_les_angevines_6.avif` | 433.7 KB | AVIF | Reference: Résidence Les Angevines | Compress image (save ~50-70%). |
| `/pic/l_hotel_des_monnaies_niel_1.avif` | 226.2 KB | AVIF | References Portfolio, Reference: Place Jean Jaurès, Reference: Centre Culturel Simone Signoret, Reference: L’hôtel des Monnaies-Niel, Reference: Residence l'Aygues, Reference: Réfectoire de Coudoux, Reference: Réhabilitation du collège Paul Cézanne, Reference: Résidence Les Angevines | Compress image (save ~50-70%). |
| `/pic/centre_culturel_simone_signoret_2.avif` | 223.1 KB | AVIF | Reference: Centre Culturel Simone Signoret | Compress image (save ~50-70%). |
| `/pic/residence_les_angevines_1.avif` | 185.1 KB | AVIF | References Portfolio, Reference: Résidence Les Angevines | Compress image (save ~50-70%). |
| `/pic/centre_culturel_simone_signoret_1.avif` | 172.7 KB | AVIF | References Portfolio, Reference: Place Jean Jaurès, Reference: Centre Culturel Simone Signoret, Reference: L’hôtel des Monnaies-Niel, Reference: Residence l'Aygues, Reference: Réfectoire de Coudoux, Reference: Réhabilitation du collège Paul Cézanne, Reference: Résidence Les Angevines | Compress image (save ~50-70%). |
| `/pic/residence_les_angevines_8.avif` | 151.8 KB | AVIF | Reference: Résidence Les Angevines | Compress image (save ~50-70%). |
| `/pic/l_hotel_des_monnaies_niel_4.avif` | 146.6 KB | AVIF | Reference: L’hôtel des Monnaies-Niel | Compress image (save ~50-70%). |

---

## 📂 Detailed Page Breakdown

### 📄 Home Page (`/`)
- **Total Payload Size:** `125.6 KB`
- **Response Speed:** `6.4 ms`

| Media Asset | Physical Path | File Size | Status |
| :--- | :--- | :---: | :--- |
| `/img/logo-nav.png` | `img\logo-nav.png` | 10.2 KB | 🟢 OK |
| `/img/footer-construction.png` | `img\footer-construction.png` | 20.7 KB | 🟢 OK |
| `/img/home-building-crane-wide.png` | `img\home-building-crane-wide.png` | 39.2 KB | 🟢 OK |
| `/img/mobile/home-building-crane-wide.avif` | `img\mobile\home-building-crane-wide.avif` | 12.9 KB | 🟢 OK |

### 📄 About Us (`/aboutUs`)
- **Total Payload Size:** `187.7 KB`
- **Response Speed:** `25.6 ms`

| Media Asset | Physical Path | File Size | Status |
| :--- | :--- | :---: | :--- |
| `/img/logo-nav.png` | `img\logo-nav.png` | 10.2 KB | 🟢 OK |
| `/img/ludovic.jpg` | `img\ludovic.jpg` | 44.3 KB | 🟢 OK |
| `/img/footer-construction.png` | `img\footer-construction.png` | 20.7 KB | 🟢 OK |
| `/img/home-building-crane-wide.png` | `img\home-building-crane-wide.png` | 39.2 KB | 🟢 OK |
| `/img/mobile/home-building-crane-wide.avif` | `img\mobile\home-building-crane-wide.avif` | 12.9 KB | 🟢 OK |

### 📄 Clients (`/clients`)
- **Total Payload Size:** `1.15 MB`
- **Response Speed:** `4.6 ms`

| Media Asset | Physical Path | File Size | Status |
| :--- | :--- | :---: | :--- |
| `/img/logo-nav.png` | `img\logo-nav.png` | 10.2 KB | 🟢 OK |
| `/img/logo/alpes.png` | `img\logo\alpes.png` | 46.5 KB | 🟢 OK |
| `/img/logo/chateauneuf.png` | `img\logo\chateauneuf.png` | 51.0 KB | 🟢 OK |
| `/img/logo/citadis.png` | `img\logo\citadis.png` | 104.3 KB | 🟢 OK |
| `/img/logo/coudoux.png` | `img\logo\coudoux.png` | 121.4 KB | 🟢 OK |
| `/img/logo/GrandDelta.png` | `img\logo\GrandDelta.png` | 44.6 KB | 🟢 OK |
| `/img/logo/vaucluseSapeurs.png` | `img\logo\vaucluseSapeurs.png` | 71.5 KB | 🟢 OK |
| `/img/logo/justice.png` | `img\logo\justice.png` | 74.6 KB | 🟢 OK |
| `/img/logo/crous.png` | `img\logo\crous.png` | 19.3 KB | 🟢 OK |
| `/img/logo/Logo_ville_Vitrolles.png` | `img\logo\Logo_ville_Vitrolles.png` | 39.9 KB | 🟢 OK |
| `/img/logo/onf.png` | `img\logo\onf.png` | 24.8 KB | 🟢 OK |
| `/img/logo/paysdapt.jpg` | `img\logo\paysdapt.jpg` | 9.8 KB | 🟢 OK |
| `/img/logo/provenceAlpes.png` | `img\logo\provenceAlpes.png` | 21.0 KB | 🟢 OK |
| `/img/logo/sdis.png` | `img\logo\sdis.png` | 99.7 KB | 🟢 OK |
| `/img/logo/soleam.png` | `img\logo\soleam.png` | 74.6 KB | 🟢 OK |
| `/img/logo/talpesdusud.svg` | `img\logo\talpesdusud.svg` | 35.6 KB | 🟢 OK |
| `/img/logo/territoire.png` | `img\logo\territoire.png` | 69.9 KB | 🟢 OK |
| `/img/logo/var.svg.png` | `img\logo\var.svg.png` | 35.4 KB | 🟢 OK |
| `/img/logo/vaucluse.svg.png` | `img\logo\vaucluse.svg.png` | 106.3 KB | 🟢 OK |
| `/img/logo/ville.png` | `img\logo\ville.png` | 8.6 KB | 🟢 OK |
| `/img/footer-construction.png` | `img\footer-construction.png` | 20.7 KB | 🟢 OK |
| `/img/home-building-crane-wide.png` | `img\home-building-crane-wide.png` | 39.2 KB | 🟢 OK |
| `/img/mobile/home-building-crane-wide.avif` | `img\mobile\home-building-crane-wide.avif` | 12.9 KB | 🟢 OK |

### 📄 Contact (`/contact`)
- **Total Payload Size:** `150.3 KB`
- **Response Speed:** `13.2 ms`

| Media Asset | Physical Path | File Size | Status |
| :--- | :--- | :---: | :--- |
| `/img/logo-nav.png` | `img\logo-nav.png` | 10.2 KB | 🟢 OK |
| `/img/footer-construction.png` | `img\footer-construction.png` | 20.7 KB | 🟢 OK |
| `/img/home-building-crane-wide.png` | `img\home-building-crane-wide.png` | 39.2 KB | 🟢 OK |
| `/img/mobile/home-building-crane-wide.avif` | `img\mobile\home-building-crane-wide.avif` | 12.9 KB | 🟢 OK |
| `/img/contact-crane-bg.png` | `img\contact-crane-bg.png` | 26.3 KB | 🟢 OK |

### 📄 Legal Notices (`/legalNotices`)
- **Total Payload Size:** `119.2 KB`
- **Response Speed:** `23.4 ms`

| Media Asset | Physical Path | File Size | Status |
| :--- | :--- | :---: | :--- |
| `/img/logo-nav.png` | `img\logo-nav.png` | 10.2 KB | 🟢 OK |
| `/img/footer-construction.png` | `img\footer-construction.png` | 20.7 KB | 🟢 OK |
| `/img/home-building-crane-wide.png` | `img\home-building-crane-wide.png` | 39.2 KB | 🟢 OK |
| `/img/mobile/home-building-crane-wide.avif` | `img\mobile\home-building-crane-wide.avif` | 12.9 KB | 🟢 OK |

### 📄 Privacy Policy (`/privacyPolicy`)
- **Total Payload Size:** `118.3 KB`
- **Response Speed:** `15.3 ms`

| Media Asset | Physical Path | File Size | Status |
| :--- | :--- | :---: | :--- |
| `/img/logo-nav.png` | `img\logo-nav.png` | 10.2 KB | 🟢 OK |
| `/img/footer-construction.png` | `img\footer-construction.png` | 20.7 KB | 🟢 OK |
| `/img/home-building-crane-wide.png` | `img\home-building-crane-wide.png` | 39.2 KB | 🟢 OK |
| `/img/mobile/home-building-crane-wide.avif` | `img\mobile\home-building-crane-wide.avif` | 12.9 KB | 🟢 OK |

### 📄 Professions / Services (`/professions`)
- **Total Payload Size:** `321.4 KB`
- **Response Speed:** `16.1 ms`

| Media Asset | Physical Path | File Size | Status |
| :--- | :--- | :---: | :--- |
| `/img/logo-nav.png` | `img\logo-nav.png` | 10.2 KB | 🟢 OK |
| `/img/professions-title-mobile.jpg` | `img\professions-title-mobile.jpg` | 26.0 KB | 🟢 OK |
| `/img/opc-desktop.jpg` | `img\opc-desktop.jpg` | 33.4 KB | 🟢 OK |
| `/img/moex-desktop.avif` | `img\moex-desktop.avif` | 29.4 KB | 🟢 OK |
| `/img/illustrations-articles-de-blog.png` | `img\illustrations-articles-de-blog.png` | 10.5 KB | 🟢 OK |
| `/img/footer-construction.png` | `img\footer-construction.png` | 20.7 KB | 🟢 OK |
| `/img/home-building-crane-wide.png` | `img\home-building-crane-wide.png` | 39.2 KB | 🟢 OK |
| `/img/mobile/home-building-crane-wide.avif` | `img\mobile\home-building-crane-wide.avif` | 12.9 KB | 🟢 OK |
| `/img/profession-cta.jpg` | `img\profession-cta.jpg` | 74.7 KB | 🟢 OK |

### 📄 References Portfolio (`/references`)
- **Total Payload Size:** `2.19 MB`
- **Response Speed:** `16.6 ms`

| Media Asset | Physical Path | File Size | Status |
| :--- | :--- | :---: | :--- |
| `/img/logo-nav.png` | `img\logo-nav.png` | 10.2 KB | 🟢 OK |
| `/pic/amenagement_de_la_place_jean_jaures_1.avif` | `pic\amenagement_de_la_place_jean_jaures_1.avif` | 911.2 KB | 🟡 Large (>300KB) |
| `/pic/centre_culturel_simone_signoret_1.avif` | `pic\centre_culturel_simone_signoret_1.avif` | 172.7 KB | 🟢 OK |
| `/pic/l_hotel_des_monnaies_niel_1.avif` | `pic\l_hotel_des_monnaies_niel_1.avif` | 226.2 KB | 🟢 OK |
| `/pic/residence_l_aygues_1.avif` | `pic\residence_l_aygues_1.avif` | 82.6 KB | 🟢 OK |
| `/pic/refectoire_de_coudoux_1.avif` | `pic\refectoire_de_coudoux_1.avif` | 31.9 KB | 🟢 OK |
| `/pic/rehabilitation_du_college_paul_cezanne_1.avif` | `pic\rehabilitation_du_college_paul_cezanne_1.avif` | 41.0 KB | 🟢 OK |
| `/pic/residence_les_angevines_1.avif` | `pic\residence_les_angevines_1.avif` | 185.1 KB | 🟢 OK |
| `/img/footer-construction.png` | `img\footer-construction.png` | 20.7 KB | 🟢 OK |
| `/pic/mobile/amenagement_de_la_place_jean_jaures_1.avif` | `pic\mobile\amenagement_de_la_place_jean_jaures_1.avif` | 140.6 KB | 🟢 OK |
| `/pic/mobile/centre_culturel_simone_signoret_1.avif` | `pic\mobile\centre_culturel_simone_signoret_1.avif` | 39.3 KB | 🟢 OK |
| `/pic/mobile/l_hotel_des_monnaies_niel_1.avif` | `pic\mobile\l_hotel_des_monnaies_niel_1.avif` | 136.7 KB | 🟢 OK |
| `/pic/mobile/residence_l_aygues_1.avif` | `pic\mobile\residence_l_aygues_1.avif` | 56.8 KB | 🟢 OK |
| `/pic/mobile/residence_les_angevines_1.avif` | `pic\mobile\residence_les_angevines_1.avif` | 83.7 KB | 🟢 OK |
| `/img/home-building-crane-wide.png` | `img\home-building-crane-wide.png` | 39.2 KB | 🟢 OK |
| `/img/mobile/home-building-crane-wide.avif` | `img\mobile\home-building-crane-wide.avif` | 12.9 KB | 🟢 OK |

### 📄 Sitemap (`/sitemap`)
- **Total Payload Size:** `2.8 KB`
- **Response Speed:** `27 ms`

*No media assets found on this page.*

### 📄 Reference: Place Jean Jaurès (`/reference/1`)
- **Total Payload Size:** `3.51 MB`
- **Response Speed:** `18.1 ms`

| Media Asset | Physical Path | File Size | Status |
| :--- | :--- | :---: | :--- |
| `/img/logo-nav.png` | `img\logo-nav.png` | 10.2 KB | 🟢 OK |
| `/pic/amenagement_de_la_place_jean_jaures_1.avif` | `pic\amenagement_de_la_place_jean_jaures_1.avif` | 911.2 KB | 🟡 Large (>300KB) |
| `/pic/amenagement_de_la_place_jean_jaures_2.avif` | `pic\amenagement_de_la_place_jean_jaures_2.avif` | 144.4 KB | 🟢 OK |
| `/pic/amenagement_de_la_place_jean_jaures_3.avif` | `pic\amenagement_de_la_place_jean_jaures_3.avif` | 91.2 KB | 🟢 OK |
| `/pic/amenagement_de_la_place_jean_jaures_4.avif` | `pic\amenagement_de_la_place_jean_jaures_4.avif` | 1.62 MB | 🔴 Too Heavy (>1MB) |
| `/pic/centre_culturel_simone_signoret_1.avif` | `pic\centre_culturel_simone_signoret_1.avif` | 172.7 KB | 🟢 OK |
| `/pic/l_hotel_des_monnaies_niel_1.avif` | `pic\l_hotel_des_monnaies_niel_1.avif` | 226.2 KB | 🟢 OK |
| `/pic/residence_l_aygues_1.avif` | `pic\residence_l_aygues_1.avif` | 82.6 KB | 🟢 OK |
| `/pic/refectoire_de_coudoux_1.avif` | `pic\refectoire_de_coudoux_1.avif` | 31.9 KB | 🟢 OK |
| `/img/footer-construction.png` | `img\footer-construction.png` | 20.7 KB | 🟢 OK |
| `/pic/mobile/amenagement_de_la_place_jean_jaures_1.avif` | `pic\mobile\amenagement_de_la_place_jean_jaures_1.avif` | 140.6 KB | 🟢 OK |
| `/img/home-building-crane-wide.png` | `img\home-building-crane-wide.png` | 39.2 KB | 🟢 OK |
| `/img/mobile/home-building-crane-wide.avif` | `img\mobile\home-building-crane-wide.avif` | 12.9 KB | 🟢 OK |

### 📄 Reference: Centre Culturel Simone Signoret (`/reference/2`)
- **Total Payload Size:** `2.61 MB`
- **Response Speed:** `29.1 ms`

| Media Asset | Physical Path | File Size | Status |
| :--- | :--- | :---: | :--- |
| `/img/logo-nav.png` | `img\logo-nav.png` | 10.2 KB | 🟢 OK |
| `/pic/centre_culturel_simone_signoret_1.avif` | `pic\centre_culturel_simone_signoret_1.avif` | 172.7 KB | 🟢 OK |
| `/pic/centre_culturel_simone_signoret_2.avif` | `pic\centre_culturel_simone_signoret_2.avif` | 223.1 KB | 🟢 OK |
| `/pic/centre_culturel_simone_signoret_3.avif` | `pic\centre_culturel_simone_signoret_3.avif` | 113.1 KB | 🟢 OK |
| `/pic/centre_culturel_simone_signoret_4.avif` | `pic\centre_culturel_simone_signoret_4.avif` | 74.0 KB | 🟢 OK |
| `/pic/centre_culturel_simone_signoret_5.avif` | `pic\centre_culturel_simone_signoret_5.avif` | 14.4 KB | 🟢 OK |
| `/pic/centre_culturel_simone_signoret_6.avif` | `pic\centre_culturel_simone_signoret_6.avif` | 519.6 KB | 🟡 Large (>300KB) |
| `/pic/centre_culturel_simone_signoret_7.avif` | `pic\centre_culturel_simone_signoret_7.avif` | 123.5 KB | 🟢 OK |
| `/pic/amenagement_de_la_place_jean_jaures_1.avif` | `pic\amenagement_de_la_place_jean_jaures_1.avif` | 911.2 KB | 🟡 Large (>300KB) |
| `/pic/l_hotel_des_monnaies_niel_1.avif` | `pic\l_hotel_des_monnaies_niel_1.avif` | 226.2 KB | 🟢 OK |
| `/pic/residence_l_aygues_1.avif` | `pic\residence_l_aygues_1.avif` | 82.6 KB | 🟢 OK |
| `/pic/refectoire_de_coudoux_1.avif` | `pic\refectoire_de_coudoux_1.avif` | 31.9 KB | 🟢 OK |
| `/img/footer-construction.png` | `img\footer-construction.png` | 20.7 KB | 🟢 OK |
| `/pic/mobile/centre_culturel_simone_signoret_1.avif` | `pic\mobile\centre_culturel_simone_signoret_1.avif` | 39.3 KB | 🟢 OK |
| `/img/home-building-crane-wide.png` | `img\home-building-crane-wide.png` | 39.2 KB | 🟢 OK |
| `/img/mobile/home-building-crane-wide.avif` | `img\mobile\home-building-crane-wide.avif` | 12.9 KB | 🟢 OK |

### 📄 Reference: L’hôtel des Monnaies-Niel (`/reference/3`)
- **Total Payload Size:** `3.34 MB`
- **Response Speed:** `6.2 ms`

| Media Asset | Physical Path | File Size | Status |
| :--- | :--- | :---: | :--- |
| `/img/logo-nav.png` | `img\logo-nav.png` | 10.2 KB | 🟢 OK |
| `/pic/l_hotel_des_monnaies_niel_1.avif` | `pic\l_hotel_des_monnaies_niel_1.avif` | 226.2 KB | 🟢 OK |
| `/pic/l_hotel_des_monnaies_niel_2.avif` | `pic\l_hotel_des_monnaies_niel_2.avif` | 36.0 KB | 🟢 OK |
| `/pic/l_hotel_des_monnaies_niel_3.avif` | `pic\l_hotel_des_monnaies_niel_3.avif` | 537.8 KB | 🟡 Large (>300KB) |
| `/pic/l_hotel_des_monnaies_niel_4.avif` | `pic\l_hotel_des_monnaies_niel_4.avif` | 146.6 KB | 🟢 OK |
| `/pic/l_hotel_des_monnaies_niel_5.avif` | `pic\l_hotel_des_monnaies_niel_5.avif` | 19.1 KB | 🟢 OK |
| `/pic/l_hotel_des_monnaies_niel_6.avif` | `pic\l_hotel_des_monnaies_niel_6.avif` | 49.5 KB | 🟢 OK |
| `/pic/l_hotel_des_monnaies_niel_7.avif` | `pic\l_hotel_des_monnaies_niel_7.avif` | 99.3 KB | 🟢 OK |
| `/pic/l_hotel_des_monnaies_niel_8.avif` | `pic\l_hotel_des_monnaies_niel_8.avif` | 810.1 KB | 🟡 Large (>300KB) |
| `/pic/l_hotel_des_monnaies_niel_9.avif` | `pic\l_hotel_des_monnaies_niel_9.avif` | 15.0 KB | 🟢 OK |
| `/pic/amenagement_de_la_place_jean_jaures_1.avif` | `pic\amenagement_de_la_place_jean_jaures_1.avif` | 911.2 KB | 🟡 Large (>300KB) |
| `/pic/centre_culturel_simone_signoret_1.avif` | `pic\centre_culturel_simone_signoret_1.avif` | 172.7 KB | 🟢 OK |
| `/pic/residence_l_aygues_1.avif` | `pic\residence_l_aygues_1.avif` | 82.6 KB | 🟢 OK |
| `/pic/refectoire_de_coudoux_1.avif` | `pic\refectoire_de_coudoux_1.avif` | 31.9 KB | 🟢 OK |
| `/img/footer-construction.png` | `img\footer-construction.png` | 20.7 KB | 🟢 OK |
| `/pic/mobile/l_hotel_des_monnaies_niel_1.avif` | `pic\mobile\l_hotel_des_monnaies_niel_1.avif` | 136.7 KB | 🟢 OK |
| `/img/home-building-crane-wide.png` | `img\home-building-crane-wide.png` | 39.2 KB | 🟢 OK |
| `/img/mobile/home-building-crane-wide.avif` | `img\mobile\home-building-crane-wide.avif` | 12.9 KB | 🟢 OK |

### 📄 Reference: Residence l'Aygues (`/reference/4`)
- **Total Payload Size:** `1.58 MB`
- **Response Speed:** `19.5 ms`

| Media Asset | Physical Path | File Size | Status |
| :--- | :--- | :---: | :--- |
| `/img/logo-nav.png` | `img\logo-nav.png` | 10.2 KB | 🟢 OK |
| `/pic/residence_l_aygues_1.avif` | `pic\residence_l_aygues_1.avif` | 82.6 KB | 🟢 OK |
| `/pic/amenagement_de_la_place_jean_jaures_1.avif` | `pic\amenagement_de_la_place_jean_jaures_1.avif` | 911.2 KB | 🟡 Large (>300KB) |
| `/pic/centre_culturel_simone_signoret_1.avif` | `pic\centre_culturel_simone_signoret_1.avif` | 172.7 KB | 🟢 OK |
| `/pic/l_hotel_des_monnaies_niel_1.avif` | `pic\l_hotel_des_monnaies_niel_1.avif` | 226.2 KB | 🟢 OK |
| `/pic/refectoire_de_coudoux_1.avif` | `pic\refectoire_de_coudoux_1.avif` | 31.9 KB | 🟢 OK |
| `/img/footer-construction.png` | `img\footer-construction.png` | 20.7 KB | 🟢 OK |
| `/pic/mobile/residence_l_aygues_1.avif` | `pic\mobile\residence_l_aygues_1.avif` | 56.8 KB | 🟢 OK |
| `/img/home-building-crane-wide.png` | `img\home-building-crane-wide.png` | 39.2 KB | 🟢 OK |
| `/img/mobile/home-building-crane-wide.avif` | `img\mobile\home-building-crane-wide.avif` | 12.9 KB | 🟢 OK |

### 📄 Reference: Réfectoire de Coudoux (`/reference/5`)
- **Total Payload Size:** `1.80 MB`
- **Response Speed:** `17.5 ms`

| Media Asset | Physical Path | File Size | Status |
| :--- | :--- | :---: | :--- |
| `/img/logo-nav.png` | `img\logo-nav.png` | 10.2 KB | 🟢 OK |
| `/pic/refectoire_de_coudoux_1.avif` | `pic\refectoire_de_coudoux_1.avif` | 31.9 KB | 🟢 OK |
| `/pic/refectoire_de_coudoux_2.avif` | `pic\refectoire_de_coudoux_2.avif` | 37.8 KB | 🟢 OK |
| `/pic/refectoire_de_coudoux_3.avif` | `pic\refectoire_de_coudoux_3.avif` | 22.9 KB | 🟢 OK |
| `/pic/refectoire_de_coudoux_4.avif` | `pic\refectoire_de_coudoux_4.avif` | 26.1 KB | 🟢 OK |
| `/pic/refectoire_de_coudoux_5.avif` | `pic\refectoire_de_coudoux_5.avif` | 131.8 KB | 🟢 OK |
| `/pic/refectoire_de_coudoux_6.avif` | `pic\refectoire_de_coudoux_6.avif` | 21.3 KB | 🟢 OK |
| `/pic/refectoire_de_coudoux_7.avif` | `pic\refectoire_de_coudoux_7.avif` | 31.5 KB | 🟢 OK |
| `/pic/amenagement_de_la_place_jean_jaures_1.avif` | `pic\amenagement_de_la_place_jean_jaures_1.avif` | 911.2 KB | 🟡 Large (>300KB) |
| `/pic/centre_culturel_simone_signoret_1.avif` | `pic\centre_culturel_simone_signoret_1.avif` | 172.7 KB | 🟢 OK |
| `/pic/l_hotel_des_monnaies_niel_1.avif` | `pic\l_hotel_des_monnaies_niel_1.avif` | 226.2 KB | 🟢 OK |
| `/pic/residence_l_aygues_1.avif` | `pic\residence_l_aygues_1.avif` | 82.6 KB | 🟢 OK |
| `/img/footer-construction.png` | `img\footer-construction.png` | 20.7 KB | 🟢 OK |
| `/img/home-building-crane-wide.png` | `img\home-building-crane-wide.png` | 39.2 KB | 🟢 OK |
| `/img/mobile/home-building-crane-wide.avif` | `img\mobile\home-building-crane-wide.avif` | 12.9 KB | 🟢 OK |

### 📄 Reference: Réhabilitation du collège Paul Cézanne (`/reference/6`)
- **Total Payload Size:** `1.76 MB`
- **Response Speed:** `27.3 ms`

| Media Asset | Physical Path | File Size | Status |
| :--- | :--- | :---: | :--- |
| `/img/logo-nav.png` | `img\logo-nav.png` | 10.2 KB | 🟢 OK |
| `/pic/rehabilitation_du_college_paul_cezanne_1.avif` | `pic\rehabilitation_du_college_paul_cezanne_1.avif` | 41.0 KB | 🟢 OK |
| `/pic/rehabilitation_du_college_paul_cezanne_2.avif` | `pic\rehabilitation_du_college_paul_cezanne_2.avif` | 20.4 KB | 🟢 OK |
| `/pic/rehabilitation_du_college_paul_cezanne_3.avif` | `pic\rehabilitation_du_college_paul_cezanne_3.avif` | 43.1 KB | 🟢 OK |
| `/pic/rehabilitation_du_college_paul_cezanne_4.avif` | `pic\rehabilitation_du_college_paul_cezanne_4.avif` | 56.2 KB | 🟢 OK |
| `/pic/rehabilitation_du_college_paul_cezanne_5.avif` | `pic\rehabilitation_du_college_paul_cezanne_5.avif` | 39.3 KB | 🟢 OK |
| `/pic/rehabilitation_du_college_paul_cezanne_6.avif` | `pic\rehabilitation_du_college_paul_cezanne_6.avif` | 64.2 KB | 🟢 OK |
| `/pic/amenagement_de_la_place_jean_jaures_1.avif` | `pic\amenagement_de_la_place_jean_jaures_1.avif` | 911.2 KB | 🟡 Large (>300KB) |
| `/pic/centre_culturel_simone_signoret_1.avif` | `pic\centre_culturel_simone_signoret_1.avif` | 172.7 KB | 🟢 OK |
| `/pic/l_hotel_des_monnaies_niel_1.avif` | `pic\l_hotel_des_monnaies_niel_1.avif` | 226.2 KB | 🟢 OK |
| `/pic/residence_l_aygues_1.avif` | `pic\residence_l_aygues_1.avif` | 82.6 KB | 🟢 OK |
| `/img/footer-construction.png` | `img\footer-construction.png` | 20.7 KB | 🟢 OK |
| `/img/home-building-crane-wide.png` | `img\home-building-crane-wide.png` | 39.2 KB | 🟢 OK |
| `/img/mobile/home-building-crane-wide.avif` | `img\mobile\home-building-crane-wide.avif` | 12.9 KB | 🟢 OK |

### 📄 Reference: Résidence Les Angevines (`/reference/7`)
- **Total Payload Size:** `4.08 MB`
- **Response Speed:** `5.1 ms`

| Media Asset | Physical Path | File Size | Status |
| :--- | :--- | :---: | :--- |
| `/img/logo-nav.png` | `img\logo-nav.png` | 10.2 KB | 🟢 OK |
| `/pic/residence_les_angevines_1.avif` | `pic\residence_les_angevines_1.avif` | 185.1 KB | 🟢 OK |
| `/pic/residence_les_angevines_2.avif` | `pic\residence_les_angevines_2.avif` | 482.9 KB | 🟡 Large (>300KB) |
| `/pic/residence_les_angevines_3.avif` | `pic\residence_les_angevines_3.avif` | 66.2 KB | 🟢 OK |
| `/pic/residence_les_angevines_4.avif` | `pic\residence_les_angevines_4.avif` | 580.0 KB | 🟡 Large (>300KB) |
| `/pic/residence_les_angevines_5.avif` | `pic\residence_les_angevines_5.avif` | 545.2 KB | 🟡 Large (>300KB) |
| `/pic/residence_les_angevines_6.avif` | `pic\residence_les_angevines_6.avif` | 433.7 KB | 🟡 Large (>300KB) |
| `/pic/residence_les_angevines_7.avif` | `pic\residence_les_angevines_7.avif` | 112.4 KB | 🟢 OK |
| `/pic/residence_les_angevines_8.avif` | `pic\residence_les_angevines_8.avif` | 151.8 KB | 🟢 OK |
| `/pic/amenagement_de_la_place_jean_jaures_1.avif` | `pic\amenagement_de_la_place_jean_jaures_1.avif` | 911.2 KB | 🟡 Large (>300KB) |
| `/pic/centre_culturel_simone_signoret_1.avif` | `pic\centre_culturel_simone_signoret_1.avif` | 172.7 KB | 🟢 OK |
| `/pic/l_hotel_des_monnaies_niel_1.avif` | `pic\l_hotel_des_monnaies_niel_1.avif` | 226.2 KB | 🟢 OK |
| `/pic/residence_l_aygues_1.avif` | `pic\residence_l_aygues_1.avif` | 82.6 KB | 🟢 OK |
| `/img/footer-construction.png` | `img\footer-construction.png` | 20.7 KB | 🟢 OK |
| `/pic/mobile/residence_les_angevines_1.avif` | `pic\mobile\residence_les_angevines_1.avif` | 83.7 KB | 🟢 OK |
| `/img/home-building-crane-wide.png` | `img\home-building-crane-wide.png` | 39.2 KB | 🟢 OK |
| `/img/mobile/home-building-crane-wide.avif` | `img\mobile\home-building-crane-wide.avif` | 12.9 KB | 🟢 OK |

---

## 🛠️ How to Reproduce This Audit

This performance audit is fully automated and can be executed at any time to verify improvements as images are optimized or code is updated.

### Prerequisites
- PHP 8.0+ installed and in your command line path (verified: PHP 8.3.0 is active).
- php-curl extension enabled in your php.ini.

### Step-by-Step Instructions

1. Open your terminal in the root folder of this project (`C:\Users\orian\code\APSI-WEB`).
2. Run the audit script using PHP:
   ```bash
   php scripts/performance_test.php
   ```
3. The script will automatically spin up a temporary PHP background server, crawl all pages, calculate live asset weights, shut down the server, and regenerate this report (`PERFORMANCE_REPORT.md`).

### Optimization Toolbox Recommendations
- **For automated image optimizations:** You can use tools like CLI `imagemin` or python `pillow` scripts to automatically compress all PNG and JPG files in `/img` and `/pic` to WebP or smaller sizes.
- **For CSS/JS optimization:** The styles are written in discrete CSS files (e.g. `css/aboutUs.css`). Consider combining them or minifying them for production to reduce asset roundtrips.

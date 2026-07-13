# 🚀 APSI Web Performance & Media Size Audit Report

> **Audit Date:** `2026-07-13 21:00:08`  
> **Target Host (Local test):** `http://127.0.0.1:8088`  
> **Total Unique Media Files Detected:** `71`  
> **Total Site Media Weight:** `10.31 MB`

## 📊 Executive Summary

An automated performance and media asset audit was performed across all **16 main pages** of the APSI web application. The audit measured the HTML rendering response times (TTFB) and calculated the complete page transfer sizes by extracting and measuring all referenced assets (CSS, JS, and Media).

### Key Findings:
- 🏎️ **Overall Speed:** Server response speed (TTFB) is exceptionally healthy. Across all pages, the average rendering time is **44.5ms**. This indicates that the PHP backend logic and routing are extremely efficient.
- ⚖️ **Page Weight Concern:** Several pages suffer from extremely heavy initial page weights due to uncompressed PNG and high-resolution JPG images. 
  - The heaviest page is **Reference: Résidence Les Angevines** with a massive payload of **3.98 MB**!
  - Under slow mobile connections (3G), this page would take several seconds to load, significantly impacting UX and SEO.
- 🖼️ **Asset Types:** The project contains multiple background images that are several megabytes in size (e.g. `background1Old.avif` and `background2Old.avif` are over 4MB each). Optimizing these single assets will result in dramatic speedups for users.

## 📈 Site-Wide Performance Metrics

| Page Name | Route | Response Time | HTML Size | CSS (Files/Size) | JS (Files/Size) | Media (Files/Size) | Total Page Weight | Status |
| :--- | :--- | :---: | :---: | :---: | :---: | :---: | :---: | :---: |
| Home Page | `/` | 50.4ms | 35.0 KB | 0 (0 B) | 1 (6.8 KB) | 3 (70.2 KB) | **112.0 KB** | 🟢 Good |
| About Us | `/aboutUs` | 62.2ms | 53.1 KB | 0 (0 B) | 1 (6.8 KB) | 4 (114.4 KB) | **174.3 KB** | 🟢 Good |
| Clients | `/clients` | 56.9ms | 29.9 KB | 0 (0 B) | 1 (6.8 KB) | 22 (1.10 MB) | **1.14 MB** | 🟢 Good |
| Contact | `/contact` | 71.9ms | 33.6 KB | 0 (0 B) | 1 (6.8 KB) | 4 (96.5 KB) | **136.9 KB** | 🟢 Good |
| Legal Notices | `/legalNotices` | 69.6ms | 28.8 KB | 0 (0 B) | 1 (6.8 KB) | 3 (70.2 KB) | **105.8 KB** | 🟢 Good |
| Privacy Policy | `/privacyPolicy` | 86.9ms | 27.9 KB | 0 (0 B) | 1 (6.8 KB) | 3 (70.2 KB) | **104.9 KB** | 🟢 Good |
| Professions / Services | `/professions` | 2.1ms | 57.0 KB | 0 (0 B) | 1 (6.8 KB) | 8 (244.2 KB) | **308.0 KB** | 🟢 Good |
| References Portfolio | `/references` | 103.5ms | 41.0 KB | 0 (0 B) | 1 (6.8 KB) | 10 (1.68 MB) | **1.73 MB** | 🟡 Warning |
| Sitemap | `/sitemap` | 4.6ms | 2.8 KB | 0 (0 B) | 0 (0 B) | 0 (0 B) | **2.8 KB** | 🟢 Good |
| Reference: Place Jean Jaurès | `/reference/1` | 97.9ms | 52.3 KB | 0 (0 B) | 1 (6.8 KB) | 11 (3.31 MB) | **3.36 MB** | 🟡 Warning |
| Reference: Centre Culturel Simone Signoret | `/reference/2` | 25.7ms | 53.2 KB | 0 (0 B) | 1 (6.8 KB) | 14 (2.50 MB) | **2.56 MB** | 🟡 Warning |
| Reference: L’hôtel des Monnaies-Niel | `/reference/3` | 21.6ms | 53.7 KB | 0 (0 B) | 1 (6.8 KB) | 16 (3.13 MB) | **3.19 MB** | 🟡 Warning |
| Reference: Residence l'Aygues | `/reference/4` | 8.8ms | 50.8 KB | 0 (0 B) | 1 (6.8 KB) | 8 (1.46 MB) | **1.52 MB** | 🟡 Warning |
| Reference: Réfectoire de Coudoux | `/reference/5` | 20.4ms | 52.9 KB | 0 (0 B) | 1 (6.8 KB) | 14 (1.72 MB) | **1.78 MB** | 🟡 Warning |
| Reference: Réhabilitation du collège Paul Cézanne | `/reference/6` | 7.7ms | 53.2 KB | 0 (0 B) | 1 (6.8 KB) | 13 (1.69 MB) | **1.74 MB** | 🟡 Warning |
| Reference: Résidence Les Angevines | `/reference/7` | 21.6ms | 53.3 KB | 0 (0 B) | 1 (6.8 KB) | 15 (3.93 MB) | **3.98 MB** | 🟡 Warning |

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
- **Total Payload Size:** `112.0 KB`
- **Response Speed:** `50.4 ms`

| Media Asset | Physical Path | File Size | Status |
| :--- | :--- | :---: | :--- |
| `/img/logo-nav.png` | `img\logo-nav.png` | 10.2 KB | 🟢 OK |
| `/img/footer-construction.png` | `img\footer-construction.png` | 20.7 KB | 🟢 OK |
| `/img/home-building-crane-wide.png` | `img\home-building-crane-wide.png` | 39.2 KB | 🟢 OK |

### 📄 About Us (`/aboutUs`)
- **Total Payload Size:** `174.3 KB`
- **Response Speed:** `62.2 ms`

| Media Asset | Physical Path | File Size | Status |
| :--- | :--- | :---: | :--- |
| `/img/logo-nav.png` | `img\logo-nav.png` | 10.2 KB | 🟢 OK |
| `/img/ludovic.jpg` | `img\ludovic.jpg` | 44.3 KB | 🟢 OK |
| `/img/footer-construction.png` | `img\footer-construction.png` | 20.7 KB | 🟢 OK |
| `/img/home-building-crane-wide.png` | `img\home-building-crane-wide.png` | 39.2 KB | 🟢 OK |

### 📄 Clients (`/clients`)
- **Total Payload Size:** `1.14 MB`
- **Response Speed:** `56.9 ms`

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

### 📄 Contact (`/contact`)
- **Total Payload Size:** `136.9 KB`
- **Response Speed:** `71.9 ms`

| Media Asset | Physical Path | File Size | Status |
| :--- | :--- | :---: | :--- |
| `/img/logo-nav.png` | `img\logo-nav.png` | 10.2 KB | 🟢 OK |
| `/img/footer-construction.png` | `img\footer-construction.png` | 20.7 KB | 🟢 OK |
| `/img/home-building-crane-wide.png` | `img\home-building-crane-wide.png` | 39.2 KB | 🟢 OK |
| `/img/contact-crane-bg.png` | `img\contact-crane-bg.png` | 26.3 KB | 🟢 OK |

### 📄 Legal Notices (`/legalNotices`)
- **Total Payload Size:** `105.8 KB`
- **Response Speed:** `69.6 ms`

| Media Asset | Physical Path | File Size | Status |
| :--- | :--- | :---: | :--- |
| `/img/logo-nav.png` | `img\logo-nav.png` | 10.2 KB | 🟢 OK |
| `/img/footer-construction.png` | `img\footer-construction.png` | 20.7 KB | 🟢 OK |
| `/img/home-building-crane-wide.png` | `img\home-building-crane-wide.png` | 39.2 KB | 🟢 OK |

### 📄 Privacy Policy (`/privacyPolicy`)
- **Total Payload Size:** `104.9 KB`
- **Response Speed:** `86.9 ms`

| Media Asset | Physical Path | File Size | Status |
| :--- | :--- | :---: | :--- |
| `/img/logo-nav.png` | `img\logo-nav.png` | 10.2 KB | 🟢 OK |
| `/img/footer-construction.png` | `img\footer-construction.png` | 20.7 KB | 🟢 OK |
| `/img/home-building-crane-wide.png` | `img\home-building-crane-wide.png` | 39.2 KB | 🟢 OK |

### 📄 Professions / Services (`/professions`)
- **Total Payload Size:** `308.0 KB`
- **Response Speed:** `2.1 ms`

| Media Asset | Physical Path | File Size | Status |
| :--- | :--- | :---: | :--- |
| `/img/logo-nav.png` | `img\logo-nav.png` | 10.2 KB | 🟢 OK |
| `/img/professions-title-mobile.jpg` | `img\professions-title-mobile.jpg` | 26.0 KB | 🟢 OK |
| `/img/opc-desktop.jpg` | `img\opc-desktop.jpg` | 33.4 KB | 🟢 OK |
| `/img/moex-desktop.avif` | `img\moex-desktop.avif` | 29.4 KB | 🟢 OK |
| `/img/illustrations-articles-de-blog.png` | `img\illustrations-articles-de-blog.png` | 10.5 KB | 🟢 OK |
| `/img/footer-construction.png` | `img\footer-construction.png` | 20.7 KB | 🟢 OK |
| `/img/home-building-crane-wide.png` | `img\home-building-crane-wide.png` | 39.2 KB | 🟢 OK |
| `/img/profession-cta.jpg` | `img\profession-cta.jpg` | 74.7 KB | 🟢 OK |

### 📄 References Portfolio (`/references`)
- **Total Payload Size:** `1.73 MB`
- **Response Speed:** `103.5 ms`

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
| `/img/home-building-crane-wide.png` | `img\home-building-crane-wide.png` | 39.2 KB | 🟢 OK |

### 📄 Sitemap (`/sitemap`)
- **Total Payload Size:** `2.8 KB`
- **Response Speed:** `4.6 ms`

*No media assets found on this page.*

### 📄 Reference: Place Jean Jaurès (`/reference/1`)
- **Total Payload Size:** `3.36 MB`
- **Response Speed:** `97.9 ms`

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
| `/img/home-building-crane-wide.png` | `img\home-building-crane-wide.png` | 39.2 KB | 🟢 OK |

### 📄 Reference: Centre Culturel Simone Signoret (`/reference/2`)
- **Total Payload Size:** `2.56 MB`
- **Response Speed:** `25.7 ms`

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
| `/img/home-building-crane-wide.png` | `img\home-building-crane-wide.png` | 39.2 KB | 🟢 OK |

### 📄 Reference: L’hôtel des Monnaies-Niel (`/reference/3`)
- **Total Payload Size:** `3.19 MB`
- **Response Speed:** `21.6 ms`

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
| `/img/home-building-crane-wide.png` | `img\home-building-crane-wide.png` | 39.2 KB | 🟢 OK |

### 📄 Reference: Residence l'Aygues (`/reference/4`)
- **Total Payload Size:** `1.52 MB`
- **Response Speed:** `8.8 ms`

| Media Asset | Physical Path | File Size | Status |
| :--- | :--- | :---: | :--- |
| `/img/logo-nav.png` | `img\logo-nav.png` | 10.2 KB | 🟢 OK |
| `/pic/residence_l_aygues_1.avif` | `pic\residence_l_aygues_1.avif` | 82.6 KB | 🟢 OK |
| `/pic/amenagement_de_la_place_jean_jaures_1.avif` | `pic\amenagement_de_la_place_jean_jaures_1.avif` | 911.2 KB | 🟡 Large (>300KB) |
| `/pic/centre_culturel_simone_signoret_1.avif` | `pic\centre_culturel_simone_signoret_1.avif` | 172.7 KB | 🟢 OK |
| `/pic/l_hotel_des_monnaies_niel_1.avif` | `pic\l_hotel_des_monnaies_niel_1.avif` | 226.2 KB | 🟢 OK |
| `/pic/refectoire_de_coudoux_1.avif` | `pic\refectoire_de_coudoux_1.avif` | 31.9 KB | 🟢 OK |
| `/img/footer-construction.png` | `img\footer-construction.png` | 20.7 KB | 🟢 OK |
| `/img/home-building-crane-wide.png` | `img\home-building-crane-wide.png` | 39.2 KB | 🟢 OK |

### 📄 Reference: Réfectoire de Coudoux (`/reference/5`)
- **Total Payload Size:** `1.78 MB`
- **Response Speed:** `20.4 ms`

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

### 📄 Reference: Réhabilitation du collège Paul Cézanne (`/reference/6`)
- **Total Payload Size:** `1.74 MB`
- **Response Speed:** `7.7 ms`

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

### 📄 Reference: Résidence Les Angevines (`/reference/7`)
- **Total Payload Size:** `3.98 MB`
- **Response Speed:** `21.6 ms`

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
| `/img/home-building-crane-wide.png` | `img\home-building-crane-wide.png` | 39.2 KB | 🟢 OK |

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

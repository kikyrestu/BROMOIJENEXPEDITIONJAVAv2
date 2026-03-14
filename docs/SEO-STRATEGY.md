# SEO Strategy — Bromo Ijen Expedition Java

> **Audit Date:** 14 Maret 2026  
> **Domain:** bromoijenexpeditionjava.com  
> **Stack:** Laravel 11 + Filament v4 + Vite + Tailwind

---

## 1. Current State Audit

### 1.1 Content Inventory

| Tipe | Jumlah | Detail |
|------|--------|--------|
| Pages | 1 | Home only |
| Blogs | 2 | 1 published, 1 draft |
| Destinations | 4 | Bromo, Ijen, Bali, Tumpak Sewu |
| Packages | 14 | 8 Bromo/Ijen variants, 4 Bali, 1 East Java, 1 Tumpak Sewu |
| SEO Metadata | 18 | All records exist but **0 focus keywords** |

### 1.2 Existing Blog Posts

| ID | Title | Status | Published |
|----|-------|--------|-----------|
| #6 | Standing on the Rim of Eternity: A Narrative Journey to Mount Bromo in 2026 | ✅ Published | 25 Feb 2026 |
| #7 | The Ultimate Bromo Ijen Practical Guide 2026: Everything You Need to Know | ❌ Draft | 25 Feb 2026 |

### 1.3 Package Pages (14 URLs)

| ID | Package Name | Slug |
|----|-------------|------|
| 1 | MOUNT BROMO IJEN TOUR FROM BALI (3D2N) | mount-bromo-ijen-tour-from-bali-3d2n |
| 2 | BALI IJEN BROMO TOUR (3D2N) | bali-ijen-bromo-tour-3d2n |
| 3 | SURABAYA BROMO IJEN SURABAYA TOUR (3D2N) | surabaya-bromo-ijen-surabaya-tour-3d2n |
| 4 | BROMO IJEN TOUR FROM SURABAYA (3D2N) | bromo-ijen-tour-from-surabaya-3d2n |
| 5 | IJEN BROMO TOUR FROM BALI JIMBARAN (3D2N) | ijen-bromo-tour-from-bali-jimbaran-3d2n |
| 6 | IJEN BROMO TOUR FROM BALI CANGGU (3D2N) | ijen-bromo-tour-from-bali-canggu-3d2n |
| 7 | IJEN BROMO TOUR FROM BALI PEMUTERAN | ijen-bromo-tour-from-bali-pemuteran |
| 8 | IJEN BROMO TOUR FROM BALI UBUD | ijen-bromo-tour-from-bali-ubud |
| 9 | BALI PRIVATE TOUR (3D2N) | bali-private-tour-3d2n |
| 10 | NUSA PENIDA TOUR (3D2N) | nusa-penida-tour-3d2n |
| 11 | BALI SOUTHERN PART DAY TOUR | bali-southern-part-day-tour |
| 12 | BALI TOUR PACKAGE (5D4N) | bali-tour-package-5d4n |
| 13 | 6-DAY EAST JAVA ULTIMATE EXPEDITION | 6-day-east-java-ultimate-expedition-bromo-ijen-tumpak-sewu-sukamade |
| 14 | BROMO TUMPAK SEWU ADVENTURE (3D2N) | bromo-tumpak-sewu-adventure-3d2n |

### 1.4 Destination Pages (4 URLs)

| ID | Name | Slug |
|----|------|------|
| 1 | Mount Bromo Tour | mount-bromo-tour |
| 2 | Ijen Crater | ijen-crater |
| 3 | Bali | bali |
| 4 | Tumpak Sewu | tumpak-sewu |

### 1.5 Technical SEO Status

| Item | Status | Notes |
|------|--------|-------|
| Sitemap.xml | ✅ | 16 URLs (homepage + 14 packages + 1 blog) |
| Robots.txt | ✅ | Blocks /admin/ and /livewire/ |
| Canonical URLs | ⚠️ | Some packages have Google search URLs as canonical (wrong!) |
| Focus Keywords | ❌ | 0 of 18 SEO records have focus keywords |
| Meta Titles | ✅ | All 18 records have titles |
| Meta Descriptions | ⚠️ | Most have descriptions, some blog references missing |
| WebP Images | ✅ | Enabled, 91% size savings |
| Alt Text | ✅ | Fixed across all hero/about/blog images |
| Structured Data | ❌ | No JSON-LD schema markup |
| OG/Social Tags | ❓ | Need to verify |

### 1.6 Critical Issues Found

1. **Canonical URLs salah** — Beberapa package punya canonical URL format `https://www.google.com/search?q=https://bromoijenexpeditionjava.com/...` — ini HARUS diperbaiki ke URL langsung
2. **Zero focus keywords** — Semua 18 SEO metadata records gak punya focus keyword
3. **Konten blog sangat sedikit** — Cuma 2 post, 1 masih draft
4. **Blog #7 masih draft** — Content sudah selesai tapi belum published
5. **Sitemap tidak include** — Blogs page (/blogs), destination pages, dan blog #7 (draft) missing
6. **Tidak ada structured data** — Perlu JSON-LD untuk TourPackage, BlogPosting, Organization, BreadcrumbList

---

## 2. Focus Keyword Strategy

### 2.1 Package Pages — Recommended Focus Keywords

| Package | Recommended Focus Keyword | Search Intent | Monthly Vol Est |
|---------|--------------------------|---------------|-----------------|
| #1 Bromo Ijen from Bali | `bromo ijen tour from bali` | Transactional | 500-1K |
| #2 Bali Ijen Bromo | `bali to ijen bromo tour` | Transactional | 200-500 |
| #3 Surabaya Bromo Ijen | `surabaya bromo ijen tour` | Transactional | 200-500 |
| #4 Bromo Ijen from Surabaya | `bromo ijen tour from surabaya` | Transactional | 300-700 |
| #5 from Jimbaran | `ijen bromo tour from jimbaran` | Transactional | 50-100 |
| #6 from Canggu | `ijen bromo tour from canggu` | Transactional | 50-100 |
| #7 from Pemuteran | `ijen bromo tour from pemuteran` | Transactional | 30-50 |
| #8 from Ubud | `ijen bromo tour from ubud` | Transactional | 100-200 |
| #9 Bali Private Tour | `bali private tour package` | Transactional | 500-1K |
| #10 Nusa Penida Tour | `nusa penida tour package` | Transactional | 1K-5K |
| #11 South Bali Day Tour | `south bali day tour` | Transactional | 200-500 |
| #12 Bali 5D4N | `bali tour package 5 days` | Transactional | 500-1K |
| #13 East Java 6D | `east java tour package` | Transactional | 200-500 |
| #14 Bromo Tumpak Sewu | `bromo tumpak sewu tour` | Transactional | 100-200 |

### 2.2 Destination Pages — Recommended Focus Keywords

| Destination | Recommended Focus Keyword |
|-------------|--------------------------|
| Mount Bromo Tour | `mount bromo tour` |
| Ijen Crater | `ijen crater tour` |
| Bali | `bali adventure tour` |
| Tumpak Sewu | `tumpak sewu waterfall tour` |

### 2.3 Blog Posts — Recommended Focus Keywords

| Blog | Recommended Focus Keyword |
|------|--------------------------|
| #6 Narrative Bromo | `mount bromo travel guide 2026` |
| #7 Bromo Ijen Guide | `bromo ijen travel guide` |

---

## 3. Blog Content Plan — 8 New Articles

### Priority & Timeline

Artikel diurutkan berdasarkan **search volume** dan **conversion potential**:

### 3.1 — Ijen Crater Blue Fire: Complete Visitor Guide 2026

| Field | Value |
|-------|-------|
| **Focus Keyword** | `ijen crater blue fire` |
| **Secondary Keywords** | ijen blue fire tour, kawah ijen blue flame, ijen crater hiking |
| **Search Volume** | 5K-10K/month |
| **Intent** | Informational → Conversion |
| **Word Count Target** | 2,500-3,000 words |
| **Priority** | 🔴 HIGH — highest search volume keyword |

**Outline:**
1. What is the Ijen Blue Fire phenomenon?
2. Best time to visit (season & time of day)
3. How to get to Ijen Crater (from Bali, Surabaya, Banyuwangi)
4. Hiking trail guide (difficulty, duration, what to bring)
5. Safety tips & gas mask requirements
6. Sulfur miners story
7. Photography tips (camera settings for blue fire)
8. Ijen Crater lake at sunrise
9. Combine with Bromo tour (link to packages)
10. FAQ section

**Internal Links:** Package #1, #2, #5, #6, #13  
**CTA:** "Book Ijen Blue Fire Tour" → WhatsApp

---

### 3.2 — Mount Bromo Sunrise: The Ultimate Viewing Guide 2026

| Field | Value |
|-------|-------|
| **Focus Keyword** | `mount bromo sunrise` |
| **Secondary Keywords** | bromo sunrise viewpoint, mount bromo sunrise tour, bromo sunrise time |
| **Search Volume** | 3K-5K/month |
| **Intent** | Informational → Conversion |
| **Word Count Target** | 2,000-2,500 words |
| **Priority** | 🔴 HIGH — core product keyword |

**Outline:**
1. Why Bromo sunrise is world-famous
2. Best viewpoints (Penanjakan 1, King Kong Hill, Seruni Point)
3. What time does sunrise happen (monthly chart)
4. How to get there (jeep tour logistics)
5. What to wear & bring (temperature drops to 3°C)
6. Sea of Sand & Hindu temple visit after sunrise
7. Photography guide
8. Rainy vs dry season comparison
9. Book your Bromo sunrise tour
10. FAQ

**Internal Links:** Package #1, #3, #4, Blog #6  
**CTA:** "Book Bromo Sunrise Tour" → WhatsApp

---

### 3.3 — How to Get from Bali to Mount Bromo (2026 Complete Guide)

| Field | Value |
|-------|-------|
| **Focus Keyword** | `bali to mount bromo` |
| **Secondary Keywords** | bali to bromo tour, how to get to bromo from bali, bali bromo transfer |
| **Search Volume** | 2K-5K/month |
| **Intent** | Informational (planning stage) |
| **Word Count Target** | 2,000-2,500 words |
| **Priority** | 🔴 HIGH — captures planning-stage tourists |

**Outline:**
1. Distance & travel time overview
2. Option 1: Private tour package (recommended) — direct link to packages
3. Option 2: Ferry + drive (budget option)
4. Option 3: Fly Bali → Surabaya → drive
5. Option 4: Train from Surabaya
6. Cost comparison table
7. Best time to make the trip
8. What to expect on the journey
9. Why private tour is worth it (comfort, time, guide)
10. FAQ

**Internal Links:** Package #1, #2, #5, #6, #7, #8  
**CTA:** "Get Free Quote for Bali to Bromo Tour" → WhatsApp

---

### 3.4 — Tumpak Sewu Waterfall: Java's Most Spectacular Waterfall Guide

| Field | Value |
|-------|-------|
| **Focus Keyword** | `tumpak sewu waterfall` |
| **Secondary Keywords** | tumpak sewu lumajang, coban sewu waterfall, tumpak sewu hike |
| **Search Volume** | 2K-5K/month |
| **Intent** | Informational → Conversion |
| **Word Count Target** | 2,000-2,500 words |
| **Priority** | 🟡 MEDIUM — destination awareness |

**Outline:**
1. Why Tumpak Sewu is called "Indonesia's Niagara"
2. Where is Tumpak Sewu (location + map)
3. How to get there (from Malang, Surabaya)
4. Upper viewpoint vs bottom trail
5. Difficulty level & fitness requirements
6. Best time to visit (dry season May-Oct)
7. Nearby attractions (Goa Tetes cave, Kapas Biru waterfall)
8. Combine with Bromo (link to package #14)
9. Photography tips
10. FAQ

**Internal Links:** Package #13, #14, Destination: Tumpak Sewu  
**CTA:** "Book Bromo + Tumpak Sewu Combo Tour"

---

### 3.5 — Perfect 3-Day Bromo Ijen Itinerary (Day-by-Day Guide)

| Field | Value |
|-------|-------|
| **Focus Keyword** | `bromo ijen itinerary 3 days` |
| **Secondary Keywords** | bromo ijen 3 day tour, bromo ijen trip plan, mount bromo ijen crater itinerary |
| **Search Volume** | 1K-2K/month |
| **Intent** | Informational (booking stage) |
| **Word Count Target** | 2,500-3,000 words |
| **Priority** | 🟡 MEDIUM — high conversion potential |

**Outline:**
1. Overview: What to expect in 3 days
2. Day 1: Pickup → drive to Bromo area → check-in → rest
3. Day 2: Bromo sunrise → Sea of Sand → drive to Ijen area
4. Day 3: Ijen midnight hike → Blue Fire → sunrise → drop-off
5. Packing list & what to wear each day
6. Cost breakdown (budget vs private tour)
7. Where to stay (hotel recommendations)
8. Food & dining along the route
9. Tips from a local guide
10. Book the exact itinerary (link to packages)

**Internal Links:** All Bromo-Ijen packages (#1-#8)  
**CTA:** "Book This Exact 3-Day Itinerary"

---

### 3.6 — Nusa Penida Complete Guide: Beaches, Snorkeling & Manta Rays

| Field | Value |
|-------|-------|
| **Focus Keyword** | `nusa penida guide` |
| **Secondary Keywords** | nusa penida tour from bali, nusa penida snorkeling manta ray, kelingking beach |
| **Search Volume** | 5K-10K/month |
| **Intent** | Informational → Conversion |
| **Word Count Target** | 2,500-3,000 words |
| **Priority** | 🟡 MEDIUM — different destination, expands reach |

**Outline:**
1. Why Nusa Penida is a must-visit
2. How to get there (fast boat from Sanur)
3. Top attractions: Kelingking Beach, Diamond Beach, Angel's Billabong
4. Snorkeling with Manta Rays at Manta Point
5. Crystal Bay diving
6. East Nusa Penida: Atuh Beach, Thousand Islands viewpoint
7. One day vs multi-day itinerary
8. Where to stay on Nusa Penida
9. Best time to visit
10. Book our Nusa Penida tour package
11. FAQ

**Internal Links:** Package #10, #12  
**CTA:** "Book 3-Day Nusa Penida Tour"

---

### 3.7 — Budget Guide: Solo Traveling East Java on a Shoestring

| Field | Value |
|-------|-------|
| **Focus Keyword** | `east java travel budget` |
| **Secondary Keywords** | solo travel east java, cheap bromo tour, backpacking java indonesia |
| **Search Volume** | 500-1K/month |
| **Intent** | Informational (awareness) |
| **Word Count Target** | 2,000-2,500 words |
| **Priority** | 🟢 LOW — awareness play |

**Outline:**
1. Why East Java is underrated
2. Daily budget breakdown ($30-50/day possible)
3. Getting around: trains, buses, ojek
4. Budget accommodation options
5. Street food guide (under $2 meals)
6. Free/cheap attractions beyond volcanoes
7. Budget vs private tour comparison
8. Safety tips for solo travelers
9. 7-day budget itinerary
10. When budget isn't enough (why private tours save time)

**Internal Links:** Package #3, #4, #13, #14, Destination pages  
**CTA:** "Compare DIY vs Private Tour Costs"

---

### 3.8 — 10 Hidden Gems in East Java Beyond Bromo & Ijen

| Field | Value |
|-------|-------|
| **Focus Keyword** | `hidden gems east java` |
| **Secondary Keywords** | east java attractions, off the beaten path java, secret places east java |
| **Search Volume** | 500-1K/month |
| **Intent** | Informational (awareness) |
| **Word Count Target** | 2,000-2,500 words |
| **Priority** | 🟢 LOW — long-tail traffic |

**Outline:**
1. Madakaripura Waterfall — the tallest in Java
2. Tumpak Sewu — the "Niagara of Indonesia"
3. Sukamade Beach — sea turtle conservation
4. Baluran National Park — "Africa van Java"
5. Papuma Beach — hidden paradise
6. Tabuhan Island — crystal clear snorkeling
7. Djawatan Forest — mystical banyan forest
8. Rainbow Village (Kampung Warna-Warni) Malang
9. Sumber Pitu Waterfall — 7 springs
10. Mount Semeru — Java's highest peak

**Internal Links:** Package #13, Destination: Tumpak Sewu  
**CTA:** "Explore East Java with a Local Expert"

---

## 4. Technical SEO Fixes Required

### 4.1 Critical Fixes (Do Immediately)

| # | Issue | Priority | Action |
|---|-------|----------|--------|
| 1 | **Canonical URLs salah** | 🔴 CRITICAL | Fix 8 packages with Google search URL canonicals → change to actual URLs |
| 2 | **Zero focus keywords** | 🔴 CRITICAL | Fill all 18 SEO records with recommended keywords |
| 3 | **Blog #7 still draft** | 🔴 HIGH | Publish the guide, it's done |
| 4 | **Sitemap incomplete** | 🔴 HIGH | Add /blogs, /destinations/*, and new blog posts |
| 5 | **No structured data** | 🟡 MEDIUM | Add JSON-LD for TourPackage, BlogPosting, Organization |
| 6 | **Missing OG image tags** | 🟡 MEDIUM | Verify Open Graph tags on all pages |

### 4.2 Canonical URL Fixes Needed

These packages have wrong canonical URLs (pointing to Google search):

| Package | Current Canonical (WRONG) | Should Be |
|---------|--------------------------|-----------|
| #1 | `google.com/search?q=...` | `bromoijenexpeditionjava.com/packages/mount-bromo-ijen-tour-from-bali-3d2n` |
| #6 | `google.com/search?q=...` | `bromoijenexpeditionjava.com/packages/ijen-bromo-tour-from-bali-canggu-3d2n` |
| #7 | `google.com/search?q=...` | `bromoijenexpeditionjava.com/packages/ijen-bromo-tour-from-bali-pemuteran` |
| #8 | `google.com/search?q=...` | `bromoijenexpeditionjava.com/packages/ijen-bromo-tour-from-bali-ubud` |
| #9 | `google.com/search?q=...` | `bromoijenexpeditionjava.com/packages/bali-private-tour-3d2n` |
| #10 | `google.com/search?q=...` | `bromoijenexpeditionjava.com/packages/nusa-penida-tour-3d2n` |
| #11 | `google.com/search?q=...` | `bromoijenexpeditionjava.com/packages/bali-southern-part-day-tour` |
| #12 | `google.com/search?q=...` | `bromoijenexpeditionjava.com/packages/bali-tour-package-5d4n` |

### 4.3 Structured Data (JSON-LD) Plan

```
Homepage:      Organization + WebSite + LocalBusiness
Package pages: TourPackage + BreadcrumbList + FAQPage
Blog posts:    BlogPosting + BreadcrumbList
Destination:   Place + BreadcrumbList
All pages:     BreadcrumbList
```

### 4.4 Sitemap Improvements

Current sitemap has 16 URLs. Should include:
- ✅ Homepage
- ✅ 14 Package pages
- ✅ 1 Published blog
- ❌ /blogs (blog listing page)
- ❌ /blogs/ultimate-bromo-ijen... (blog #7 when published)
- ❌ /destinations/mount-bromo-tour
- ❌ /destinations/ijen-crater
- ❌ /destinations/bali
- ❌ /destinations/tumpak-sewu
- ❌ /reviews
- ❌ /gallery
- ❌ Future 8 blog posts

**Target:** 30+ indexed URLs after all content is published.

---

## 5. Internal Linking Strategy

### 5.1 Hub & Spoke Model

```
                    ┌─────────────────────┐
                    │     HOMEPAGE         │
                    │  (Authority Hub)     │
                    └──────┬──────────────┘
           ┌───────────────┼───────────────────┐
           ▼               ▼                   ▼
    ┌─────────────┐ ┌─────────────┐   ┌──────────────┐
    │ DESTINATIONS│ │  PACKAGES   │   │    BLOGS     │
    │  (4 pages)  │ │ (14 pages)  │   │  (10 posts)  │
    └──────┬──────┘ └──────┬──────┘   └──────┬───────┘
           │               │                  │
           └───────────────┼──────────────────┘
                           ▼
                  (Cross-link everything)
```

### 5.2 Key Linking Rules

1. **Every blog post** harus link ke minimal 2 package pages
2. **Every package page** harus link ke relevant blog posts
3. **Destination pages** link ke semua packages & blogs terkait
4. **Bromo articles** → Link ke Ijen articles (dan sebaliknya)
5. **"Bali to Bromo" article** → Link ke SEMUA Bali-departure packages

---

## 6. On-Page SEO Checklist per Article

Untuk setiap blog post baru, pastikan:

- [ ] Focus keyword di H1 title
- [ ] Focus keyword dalam 100 kata pertama
- [ ] Focus keyword di meta title (max 60 chars)
- [ ] Focus keyword di meta description (max 155 chars)
- [ ] Focus keyword di URL slug
- [ ] H2/H3 subheadings mengandung secondary keywords
- [ ] Minimal 1 gambar dengan alt text mengandung keyword
- [ ] Internal link ke 2-3 package pages
- [ ] Internal link ke 1-2 artikel terkait lainnya
- [ ] External link ke 1-2 authoritative sources (Wikipedia, Indonesia tourism)
- [ ] FAQ section di akhir (untuk FAQ rich snippets)
- [ ] CTA button ke WhatsApp / booking
- [ ] Read time estimation

---

## 7. Execution Priority

### Phase 1 — Quick Wins (Immediate) ✅ DONE
- [x] Fix all canonical URLs (12 fixed — packages + blogs)
- [x] Fill focus keywords for all 18 SEO metadata records
- [x] Publish Blog #7 (draft → published)
- [x] Update sitemap to include all pages (16 → 29 URLs, dynamic)

### Phase 2 — Content Creation (Week 1-2)
- [x] Article #1: Ijen Crater Blue Fire (Blog #8 — published, 2800+ words)
- [ ] Article #2: Mount Bromo Sunrise
- [ ] Article #3: Bali to Mount Bromo

### Phase 3 — Content Expansion (Week 3-4)
- [ ] Article #4: Tumpak Sewu Waterfall
- [ ] Article #5: 3-Day Bromo Ijen Itinerary
- [ ] Article #6: Nusa Penida Guide

### Phase 4 — Long-Tail Content (Week 5-6)
- [ ] Article #7: Budget Solo Travel East Java
- [ ] Article #8: 10 Hidden Gems East Java

### Phase 5 — Technical Enhancement
- [ ] Add JSON-LD structured data
- [ ] Implement breadcrumbs
- [ ] Set up Google Search Console & submit sitemap
- [ ] Configure Google Analytics events for conversions

---

## 8. KPI Targets

| Metric | Current | Target (3 months) | Target (6 months) |
|--------|---------|--------------------|--------------------|
| Indexed URLs | ~16 | 30+ | 40+ |
| Blog Posts | 2 | 10 | 15+ |
| Organic Traffic | ? | 500/month | 2,000/month |
| Focus Keywords Set | 0/18 | 28/28 | All pages |
| Top 10 Rankings | 0 | 3-5 keywords | 10+ keywords |
| Avg Page Speed | ? | <3s | <2s |

---

## 9. Notes

- Semua package pages `is_active: N` (inactive) — ini mungkin karena toggle di Filament, **bukan** berarti halaman gak bisa diakses. Perlu diverifikasi.
- Blog `#1` sampai `#5` kemungkinan sudah dihapus (SEO metadata masih ada untuk Blog #1 tapi record blog-nya gone)
- SEO metadata untuk Blog #1 refers ke "10 Tips for Hiking Mount Bromo" yang sudah tidak exist — perlu cleanup
- Image optimization sudah 91% done (60/70 images optimized, WebP enabled)

---

*Document ini akan di-update seiring progress implementasi.*

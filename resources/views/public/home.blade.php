@extends('layouts.public-layout')

@section('title', 'Beranda - Posyandu Digital')

@push('head')
    <!-- Preload Critical LCP Hero Image -->
    <link rel="preload" href="{{ asset('assets/img/tim-kenanga.jpg') }}" as="image" fetchpriority="high">

    <!-- Defer GSAP Scripts to prevent render-blocking -->
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>

    <style>
        /* ── DESIGN SYSTEM ── */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            background-color: #f8faf9;
            overflow-x: hidden;
            color: #0f172a;
            -webkit-font-smoothing: antialiased;
        }

        /* ── LAYOUT ── */
        .page-wrapper {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
        }

        @media (min-width: 768px) {
            .page-wrapper {
                padding: 0 40px;
            }
        }

        /* ── SECTION SPACING ── */
        .section {
            margin-bottom: 120px;
        }

        /* ── EYEBROW LABEL ── */
        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: #0d9488;
            margin-bottom: 16px;
        }

        .eyebrow::before {
            content: '';
            display: block;
            width: 18px;
            height: 2px;
            background: #0d9488;
            border-radius: 2px;
        }

        /* ── SECTION HEADING ── */
        .section-heading {
            font-size: clamp(28px, 4vw, 44px);
            font-weight: 800;
            letter-spacing: -0.03em;
            line-height: 1.1;
            color: #0f172a;
            margin: 0 0 16px;
        }

        .section-heading em {
            font-style: normal;
            color: #0d9488;
        }

        /* ── HERO (FULL WIDTH OVERHAUL) ── */
        .hero-section-full {
            position: relative;
            width: 100%;
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 80px 0;
            overflow: hidden;
            margin-bottom: 80px;
        }

        .hero-container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
            display: grid;
            grid-template-columns: 1fr;
            align-items: center;
            gap: 48px;
        }

        @media (min-width: 768px) {
            .hero-container {
                padding: 0 40px;
            }
        }

        @media (min-width: 1024px) {
            .hero-container {
                grid-template-columns: 1fr 420px;
            }
        }

        .hero-bg-dots {
            position: absolute;
            inset: 0;
            background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
            background-size: 28px 28px;
            opacity: 0.35;
            pointer-events: none;
        }

        .hero-bg-glow {
            position: absolute;
            top: -100px;
            right: -100px;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(13, 148, 136, 0.08) 0%, transparent 65%);
            pointer-events: none;
        }

        /* Hero badge */
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px 6px 8px;
            background: #f0fdf9;
            border: 1px solid #99f6e4;
            border-radius: 100px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: #0f766e;
            margin-bottom: 28px;
        }

        .hero-badge-dot {
            width: 8px;
            height: 8px;
            background: #10b981;
            border-radius: 50%;
            animation: pulse-dot 2s infinite;
        }

        @keyframes pulse-dot {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.6;
                transform: scale(0.8);
            }
        }

        /* Hero heading */
        .hero-heading {
            font-size: clamp(36px, 5vw, 60px);
            font-weight: 800;
            letter-spacing: -0.04em;
            line-height: 1.05;
            color: #0f172a;
            margin: 0 0 24px;
        }

        .hero-heading .accent {
            color: #0d9488;
        }

        /* Hero inline image pill */
        .hero-heading .img-pill {
            display: inline-block;
            width: 56px;
            height: 40px;
            border-radius: 100px;
            overflow: hidden;
            vertical-align: middle;
            margin: 0 6px;
            border: 2px solid #e2e8f0;
            transform: rotate(-4deg);
            transition: transform 0.4s ease;
        }

        .hero-heading .img-pill:hover {
            transform: rotate(0deg);
        }

        .hero-heading .img-pill img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .hero-desc {
            font-size: 16px;
            font-weight: 400;
            line-height: 1.75;
            color: #64748b;
            max-width: 480px;
            margin: 0 0 40px;
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        /* ── BUTTONS ── */
        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 28px;
            background: #0d9488;
            color: #ffffff;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.02em;
            border-radius: 14px;
            text-decoration: none;
            transition: background 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
            box-shadow: 0 4px 14px rgba(13, 148, 136, 0.25);
        }

        .btn-primary:hover {
            background: #0f766e;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(13, 148, 136, 0.3);
        }

        .btn-primary:active {
            transform: scale(0.97);
        }

        .btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 24px;
            background: #ffffff;
            color: #374151;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.02em;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            text-decoration: none;
            transition: background 0.2s ease, border-color 0.2s ease, transform 0.2s ease;
        }

        .btn-secondary:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            transform: translateY(-2px);
        }

        /* ── HERO VISUAL ── */
        .hero-visual {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .hero-img-main {
            width: 100%;
            max-width: 380px;
            aspect-ratio: 4/3;
            border-radius: 20px;
            overflow: hidden;
            border: 3px solid #ffffff;
            box-shadow: 0 24px 48px rgba(15, 23, 42, 0.12);
            transform: rotate(-2deg);
            transition: transform 0.6s ease;
        }

        .hero-img-main:hover {
            transform: rotate(0deg);
        }

        .hero-img-main img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .hero-float-card {
            position: absolute;
            bottom: -16px;
            right: -8px;
            background: #ffffff;
            border: 1px solid #f1f5f9;
            border-radius: 16px;
            padding: 14px 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 8px 32px rgba(15, 23, 42, 0.1);
            max-width: 200px;
            transform: rotate(3deg);
            transition: transform 0.4s ease;
        }

        .hero-float-card:hover {
            transform: rotate(0deg);
        }

        .hero-float-card .icon {
            width: 40px;
            height: 40px;
            background: #f0fdf4;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .hero-float-card .icon .material-symbols-outlined {
            font-size: 22px;
            color: #16a34a;
        }

        .hero-float-card .label {
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
        }

        .hero-float-card .sublabel {
            font-size: 10px;
            font-weight: 600;
            color: #0d9488;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        /* ── MARQUEE ── */
        .marquee-section {
            position: relative;
            width: 100vw;
            left: 50%;
            right: 50%;
            margin-left: -50vw;
            margin-right: -50vw;
            overflow: hidden;
            background: #042f2e;
            border-top: 1px solid #134e4a;
            border-bottom: 1px solid #134e4a;
            padding: 18px 0;
            margin-bottom: 120px;
        }

        .marquee-section::before,
        .marquee-section::after {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            width: 120px;
            z-index: 2;
            pointer-events: none;
        }

        .marquee-section::before {
            left: 0;
            background: linear-gradient(to right, #042f2e, transparent);
        }

        .marquee-section::after {
            right: 0;
            background: linear-gradient(to left, #042f2e, transparent);
        }

        @keyframes marquee-scroll {
            from {
                transform: translateX(0);
            }

            to {
                transform: translateX(-50%);
            }
        }

        .marquee-track {
            display: flex;
            width: max-content;
            animation: marquee-scroll 32s linear infinite;
            gap: 0;
        }

        .marquee-track:hover {
            animation-play-state: paused;
        }

        .marquee-item {
            display: flex;
            align-items: center;
            gap: 0;
            white-space: nowrap;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: #5eead4;
            padding: 0 40px;
        }

        .marquee-item::before {
            content: '✦';
            margin-right: 40px;
            color: #0d9488;
            font-size: 10px;
        }

        /* ── BENTO FEATURE CARDS ── */
        .feature-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 24px;
        }

        @media (min-width: 1024px) {
            .feature-grid {
                grid-template-columns: repeat(3, 1fr);
                grid-template-rows: repeat(2, 260px);
            }
        }

        .feature-card {
            position: relative;
            background: #ffffff;
            border: 1px solid rgba(15, 23, 42, 0.05);
            border-radius: 24px;
            padding: 28px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: all 450ms cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.01);
        }

        @media (min-width: 1024px) {
            .feature-card.card-large {
                grid-column: span 2;
                grid-row: span 2;
                flex-direction: row;
                align-items: center;
                gap: 40px;
                padding: 36px;
            }
        }

        .feature-card:hover {
            transform: translateY(-6px);
            border-color: rgba(13, 148, 136, 0.25);
            box-shadow:
                0 24px 48px -15px rgba(13, 148, 136, 0.08),
                0 1px 0 rgba(255, 255, 255, 0.8) inset;
        }

        .card-large-text {
            flex: 1;
            display: flex;
            flex-direction: column;
            height: 100%;
            justify-content: space-between;
        }

        .feature-card-visual {
            width: 100%;
            max-width: 240px;
            height: 160px;
            background: linear-gradient(135deg, #f0fdf9 0%, #ccfbf1 100%);
            border-radius: 18px;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            padding: 16px;
            position: relative;
            overflow: hidden;
            box-shadow: inset 0 2px 8px rgba(13, 148, 136, 0.05);
            flex-shrink: 0;
        }

        @media (max-width: 1023px) {
            .feature-card-visual {
                margin-top: 24px;
                max-width: 100%;
            }
        }

        .mini-chart {
            display: flex;
            align-items: flex-end;
            gap: 8px;
            width: 100%;
            height: 100%;
        }

        .chart-bar {
            flex: 1;
            background: linear-gradient(to top, #0d9488, #006c49);
            border-radius: 6px 6px 0 0;
            opacity: 0.85;
            transition: all 500ms cubic-bezier(0.16, 1, 0.3, 1);
        }

        .feature-card:hover .chart-bar:nth-child(1) {
            height: 25% !important;
        }

        .feature-card:hover .chart-bar:nth-child(2) {
            height: 50% !important;
        }

        .feature-card:hover .chart-bar:nth-child(3) {
            height: 35% !important;
        }

        .feature-card:hover .chart-bar:nth-child(4) {
            height: 75% !important;
        }

        .feature-card:hover .chart-bar:nth-child(5) {
            height: 60% !important;
        }

        .feature-card:hover .chart-bar:nth-child(6) {
            height: 95% !important;
        }

        .feature-icon {
            position: relative;
            z-index: 1;
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 400ms cubic-bezier(0.16, 1, 0.3, 1);
            flex-shrink: 0;
        }

        .feature-icon .material-symbols-outlined {
            font-size: 26px;
        }

        .feature-icon.teal {
            background: #f0fdf9;
            color: #0d9488;
        }

        .feature-card:hover .feature-icon.teal {
            background: #0d9488;
            color: #ffffff;
        }

        .feature-icon.blue {
            background: #eff6ff;
            color: #2563eb;
        }

        .feature-card:hover .feature-icon.blue {
            background: #2563eb;
            color: #ffffff;
        }

        .feature-icon.indigo {
            background: #eef2ff;
            color: #4f46e5;
        }

        .feature-card:hover .feature-icon.indigo {
            background: #4f46e5;
            color: #ffffff;
        }

        .feature-card-content {
            position: relative;
            z-index: 1;
            margin-top: 24px;
        }

        .feature-card-title {
            font-size: 18px;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: #0f172a;
            margin: 0 0 8px;
        }

        .feature-card-desc {
            font-size: 14px;
            line-height: 1.6;
            color: #64748b;
            margin: 0;
        }

        /* ── SCHEDULE SECTION ── */
        .schedule-header {
            display: flex;
            flex-direction: column;
            gap: 16px;
            margin-bottom: 48px;
        }

        @media (min-width: 768px) {
            .schedule-header {
                flex-direction: row;
                align-items: flex-end;
                justify-content: space-between;
            }
        }

        .schedule-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 24px;
        }

        @media (min-width: 768px) {
            .schedule-grid {
                grid-template-columns: repeat(12, 1fr);
            }
        }

        .schedule-card {
            position: relative;
            background: #ffffff;
            border: 1px solid rgba(15, 23, 42, 0.05);
            border-radius: 24px;
            overflow: hidden;
            min-height: 280px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: all 450ms cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.01);
        }

        @media (min-width: 768px) {
            .schedule-card:first-child {
                grid-column: span 7;
            }

            .schedule-card:not(:first-child) {
                grid-column: span 5;
            }
        }

        .schedule-card:hover {
            border-color: rgba(13, 148, 136, 0.25);
            box-shadow:
                0 30px 60px -15px rgba(13, 148, 136, 0.08),
                0 1px 0 rgba(255, 255, 255, 0.8) inset;
            transform: translateY(-6px) scale(1.005);
        }

        .schedule-card-inner {
            padding: 36px;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .schedule-card-top {
            margin-bottom: 24px;
        }

        .schedule-top-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            gap: 12px;
            flex-wrap: wrap;
        }

        .badge-status {
            display: inline-block;
            padding: 6px 14px;
            font-size: 10px;
            font-weight: 750;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            border-radius: 9999px;
        }

        .badge-status.ongoing {
            background: rgba(13, 148, 136, 0.1);
            color: #0d9488;
        }

        .badge-status.upcoming {
            background: rgba(15, 23, 42, 0.06);
            color: #475569;
        }

        .badge-posyandu {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #94a3b8;
        }

        .badge-posyandu::before {
            content: '';
            width: 6px;
            height: 6px;
            background: #5eead4;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .schedule-title {
            font-size: clamp(20px, 2.5vw, 26px);
            font-weight: 800;
            letter-spacing: -0.025em;
            line-height: 1.2;
            color: #0f172a;
            margin: 0;
            transition: color 300ms ease;
        }

        .schedule-card:hover .schedule-title {
            color: #0d9488;
        }

        .schedule-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            padding-top: 24px;
            border-top: 1px solid #f1f5f9;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .meta-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: all 300ms ease;
        }

        .meta-icon .material-symbols-outlined {
            font-size: 17px;
        }

        .meta-icon.teal {
            background: #f0fdf9;
            color: #0d9488;
        }

        .meta-icon.amber {
            background: #fffbeb;
            color: #d97706;
        }

        .schedule-card:hover .meta-icon.teal {
            background: #0d9488;
            color: #ffffff;
        }

        .schedule-card:hover .meta-icon.amber {
            background: #d97706;
            color: #ffffff;
        }

        .meta-label {
            font-size: 10px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #94a3b8;
            font-weight: 600;
            margin: 0 0 2px;
        }

        .meta-value {
            font-size: 13px;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        /* Empty state */
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 60px 24px;
            background: transparent;
            border: none;
            border-radius: 0;
            max-width: 520px;
            margin: 0 auto;
        }

        .empty-state:hover {
            border-color: transparent;
        }

        .empty-icon {
            width: 72px;
            height: 72px;
            background: #f0fdf9;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
            transition: transform 0.3s ease;
        }

        .empty-state:hover .empty-icon {
            transform: scale(1.05);
        }

        .empty-icon .material-symbols-outlined {
            font-size: 34px;
            color: #0d9488;
        }

        .empty-title {
            font-size: 20px;
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 10px;
            letter-spacing: -0.02em;
        }

        .empty-desc {
            font-size: 14px;
            line-height: 1.65;
            color: #94a3b8;
            max-width: 340px;
            margin: 0;
        }

        /* ── ARTICLES ── */
        .articles-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            margin-bottom: 48px;
            gap: 16px;
            flex-wrap: wrap;
        }

        .btn-text-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 22px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 750;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #0d9488;
            text-decoration: none;
            background: rgba(13, 148, 136, 0.06);
            transition: all 300ms cubic-bezier(0.16, 1, 0.3, 1);
            white-space: nowrap;
        }

        .btn-text-link:hover {
            background: #0d9488;
            color: white;
            transform: translateY(-1.5px);
        }

        .articles-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 24px;
        }

        @media (min-width: 768px) {
            .articles-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        .article-card {
            background: #ffffff;
            border: 1px solid rgba(15, 23, 42, 0.04);
            border-radius: 24px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 100%;
            transition: all 400ms cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.01);
            padding: 16px;
        }

        .article-card:hover {
            border-color: rgba(13, 148, 136, 0.25);
            box-shadow: 0 24px 48px -15px rgba(13, 148, 136, 0.06);
            transform: translateY(-6px);
        }

        .article-img-wrap {
            display: block;
            aspect-ratio: 16/10;
            overflow: hidden;
            position: relative;
            border-radius: 16px;
        }

        .article-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 800ms cubic-bezier(0.16, 1, 0.3, 1);
        }

        .article-card:hover .article-img-wrap img {
            transform: scale(1.05);
        }

        .article-img-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(15, 23, 42, 0.1), transparent 50%);
            pointer-events: none;
        }

        .article-body {
            padding: 20px 12px 4px 12px;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .article-title {
            font-size: 18px;
            font-weight: 750;
            letter-spacing: -0.02em;
            line-height: 1.35;
            color: #0f172a;
            margin: 0 0 20px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            transition: color 300ms ease;
        }

        .article-card:hover .article-title {
            color: #0d9488;
        }

        .article-title a {
            text-decoration: none;
            color: inherit;
        }

        .article-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 18px;
            border-top: 1px solid #f8fafc;
        }

        .article-date {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        .article-date::before {
            content: '';
            width: 5px;
            height: 5px;
            background: #5eead4;
            border-radius: 50%;
        }

        .article-arrow {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 300ms cubic-bezier(0.16, 1, 0.3, 1);
            color: #94a3b8;
        }

        .article-card:hover .article-arrow {
            background: #0d9488;
            color: #ffffff;
            transform: rotate(-45deg);
        }

        .article-arrow .material-symbols-outlined {
            font-size: 16px;
        }

        /* ── CTA SECTION ── */
        .cta-section {
            position: relative;
            background: #042f2e;
            border-radius: 28px;
            overflow: hidden;
            padding: 80px 48px;
            text-align: center;
            margin-bottom: 60px;
        }

        .cta-glow-1 {
            position: absolute;
            right: -80px;
            bottom: -80px;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(13, 148, 136, 0.2) 0%, transparent 70%);
            pointer-events: none;
        }

        .cta-glow-2 {
            position: absolute;
            left: -80px;
            top: -80px;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.1) 0%, transparent 70%);
            pointer-events: none;
        }

        .cta-dots {
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(255, 255, 255, 0.06) 1px, transparent 1px);
            background-size: 24px 24px;
            pointer-events: none;
        }

        .cta-inner {
            position: relative;
            z-index: 1;
            max-width: 560px;
            margin: 0 auto;
        }

        .cta-heading {
            font-size: clamp(28px, 4vw, 44px);
            font-weight: 800;
            letter-spacing: -0.03em;
            line-height: 1.1;
            color: #ffffff;
            margin: 0 0 20px;
        }

        .cta-heading .highlight {
            color: #5eead4;
        }

        .cta-desc {
            font-size: 15px;
            line-height: 1.7;
            color: rgba(204, 251, 241, 0.65);
            max-width: 440px;
            margin: 0 auto 40px;
        }

        .btn-cta {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 16px 36px;
            background: #ffffff;
            color: #042f2e;
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            border-radius: 14px;
            text-decoration: none;
            transition: background 0.2s ease, transform 0.2s ease;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        .btn-cta:hover {
            background: #f0fdf9;
            transform: translateY(-2px);
        }

        /* ── UTIL: SECTION DIVIDER ── */
        .section-divider {
            flex: 1;
            height: 1px;
            background: #f1f5f9;
            margin: 0 24px;
            display: none;
        }

        @media (min-width: 1024px) {
            .section-divider {
                display: block;
            }
        }

        /* ── REDUCED MOTION ── */
        @media (prefers-reduced-motion: reduce) {
            .marquee-track {
                animation: none;
            }

            * {
                transition-duration: 0.01ms !important;
                animation-duration: 0.01ms !important;
            }
        }
    </style>
@endpush

@section('content')
    {{-- ══ HERO (Full-width layout) ══ --}}
    <section class="hero-section-full" id="hero">
        <div class="hero-bg-dots"></div>
        <div class="hero-bg-glow"></div>

        <div class="hero-container">
            {{-- Text --}}
            <div id="hero-text">
                <div class="hero-badge">
                    <span class="hero-badge-dot"></span>
                    Portal Kesehatan Terpadu
                </div>

                <h1 class="hero-heading">
                    Modernisasi Layanan <span class="accent">Posyandu
                        Kita.</span>
                </h1>

                <p class="hero-desc">
                    Transformasi sistem informasi kesehatan dasar melalui manajemen data terintegrasi. Memantau tumbuh
                    kembang anak kini lebih praktis, transparan, dan akurat.
                </p>

                <div class="hero-actions">
                    <a href="{{ route('public.articles.index') }}" class="btn-primary">
                        Baca Artikel Wawasan
                        <span class="material-symbols-outlined" style="font-size:18px;">arrow_forward</span>
                    </a>
                    <a href="{{ route('public.about') }}" class="btn-secondary">
                        Tentang Kami
                    </a>
                </div>
            </div>

            {{-- Visual --}}
            <div class="hero-visual" id="hero-images">
                <div class="hero-img-main">
                    <img src="{{ asset('assets/img/tim-kenanga.jpg') }}"
                        fetchpriority="high"
                        loading="eager"
                        onerror="this.src='https://images.unsplash.com/photo-1582213782179-e0d53f98f2ca?q=80&w=800&auto=format&fit=crop'"
                        alt="Tim Posyandu Kenanga">
                </div>
                <div class="hero-float-card">
                    <div class="icon">
                        <span class="material-symbols-outlined">diversity_1</span>
                    </div>
                    <div>
                        <div class="label">Layanan Prima</div>
                        <div class="sublabel">Kader Kenanga</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ══ MARQUEE (full-width, outside page-wrapper) ══ --}}
    <div class="marquee-section">
        <div class="marquee-track" aria-hidden="true">
            <span class="marquee-item">Dukung Tumbuh Kembang Balita</span>
            <span class="marquee-item">Data Terintegrasi &amp; Akurat</span>
            <span class="marquee-item">Layanan Posyandu Ramah Anak</span>
            <span class="marquee-item">Menuju Keluarga Sehat Sejahtera</span>
            <span class="marquee-item">Pencegahan Stunting Bersama</span>
            <span class="marquee-item">Imunisasi Tepat Waktu</span>
            {{-- Duplicate for seamless loop --}}
            <span class="marquee-item">Dukung Tumbuh Kembang Balita</span>
            <span class="marquee-item">Data Terintegrasi &amp; Akurat</span>
            <span class="marquee-item">Layanan Posyandu Ramah Anak</span>
            <span class="marquee-item">Menuju Keluarga Sehat Sejahtera</span>
            <span class="marquee-item">Pencegahan Stunting Bersama</span>
            <span class="marquee-item">Imunisasi Tepat Waktu</span>
        </div>
    </div>

    <div class="page-wrapper">

        {{-- ══ FITUR UTAMA ══ --}}
        <section class="section" id="fitur-section">
            <div style="display:flex;flex-direction:column;gap:8px;margin-bottom:48px;" id="fitur-header">
                <h2 class="section-heading">Fitur Unggulan <em>Posyandu Digital.</em></h2>
                <p style="font-size:15px;color:#64748b;line-height:1.7;max-width:520px;margin:8px 0 0;">
                    Kepraktisan pencatatan data balita, jadwal pemeriksaan real-time, dan grafik perkembangan kesehatan.
                </p>
            </div>

            <div class="feature-grid">
                {{-- Fitur 1 (Large Bento Card) --}}
                <div class="feature-card card-large">
                    <div class="card-large-text">
                        <div class="feature-icon teal">
                            <span class="material-symbols-outlined">monitoring</span>
                        </div>
                        <div class="feature-card-content">
                            <h4 class="feature-card-title">Monitoring Digital</h4>
                            <p class="feature-card-desc">Catat hasil pengukuran berat badan, tinggi badan, lingkar kepala,
                                dan data pertumbuhan secara instan. Data divisualisasikan langsung ke dalam grafik
                                pertumbuhan standar WHO.</p>
                        </div>
                    </div>
                    <div class="feature-card-visual">
                        <div class="mini-chart">
                            <div class="chart-bar" style="height: 15%;"></div>
                            <div class="chart-bar" style="height: 35%;"></div>
                            <div class="chart-bar" style="height: 25%;"></div>
                            <div class="chart-bar" style="height: 55%;"></div>
                            <div class="chart-bar" style="height: 45%;"></div>
                            <div class="chart-bar" style="height: 75%;"></div>
                        </div>
                    </div>
                </div>

                {{-- Fitur 2 (Medium Bento Card) --}}
                <div class="feature-card">
                    <div>
                        <div class="feature-icon blue">
                            <span class="material-symbols-outlined">calendar_today</span>
                        </div>
                        <div class="feature-card-content">
                            <h4 class="feature-card-title">Jadwal Terpadu</h4>
                            <p class="feature-card-desc">Pantau dan dapatkan notifikasi jadwal imunisasi serta pemeriksaan
                                posyandu di setiap pedukuhan secara real-time.</p>
                        </div>
                    </div>
                </div>

                {{-- Fitur 3 (Medium Bento Card) --}}
                <div class="feature-card">
                    <div>
                        <div class="feature-icon indigo">
                            <span class="material-symbols-outlined">analytics</span>
                        </div>
                        <div class="feature-card-content">
                            <h4 class="feature-card-title">Laporan Akurat</h4>
                            <p class="feature-card-desc">Akses statistik posyandu dan laporan status gizi real-time untuk
                                penanganan stunting yang lebih cepat.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        {{-- ══ JADWAL KEGIATAN ══ --}}
        <section id="jadwal" class="section" style="scroll-margin-top: 24px;">
            <div class="schedule-header" id="jadwal-header">
                <div>
                    <h2 class="section-heading">Jadwal <em>Kegiatan Posyandu.</em></h2>
                </div>
                <div class="section-divider"></div>
            </div>

            @if ($schedules->count() > 0)
                <div class="schedule-grid" id="jadwal-grid">
                    @foreach ($schedules as $index => $schedule)
                        <div class="schedule-card">
                            <div class="schedule-card-inner">
                                <div class="schedule-card-top">
                                    <div class="schedule-top-row">
                                        <span
                                            class="badge-status {{ $schedule->status === 'ongoing' ? 'ongoing' : 'upcoming' }}">
                                            {{ $schedule->status === 'ongoing' ? 'Sedang Berlangsung' : 'Segera Hadir' }}
                                        </span>
                                        @if ($schedule->posyandu)
                                            <span class="badge-posyandu">{{ $schedule->posyandu->name }}</span>
                                        @endif
                                    </div>
                                    <h3 class="schedule-title">{{ $schedule->title }}</h3>
                                </div>

                                <div class="schedule-meta">
                                    <div class="meta-item">
                                        <div class="meta-icon teal">
                                            <span class="material-symbols-outlined">calendar_today</span>
                                        </div>
                                        <div>
                                            <div class="meta-label">Tanggal</div>
                                            <div class="meta-value">
                                                {{ \Carbon\Carbon::parse($schedule->start_time)->translatedFormat('d M Y') }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="meta-item">
                                        <div class="meta-icon amber">
                                            <span class="material-symbols-outlined">location_on</span>
                                        </div>
                                        <div>
                                            <div class="meta-label">Lokasi</div>
                                            <div class="meta-value">{{ $schedule->location ?: 'Posyandu Kenanga' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <span class="material-symbols-outlined">event_busy</span>
                    </div>
                    <h3 class="empty-title">Belum Ada Jadwal Terdekat</h3>
                    <p class="empty-desc">Belum ada kegiatan posyandu yang dijadwalkan. Kunjungi kembali halaman ini secara
                        berkala.</p>
                </div>
            @endif
        </section>


        {{-- ══ ARTIKEL KESEHATAN ══ --}}
        <section class="section" id="artikel-section">
            <div class="articles-header">
                <div>
                    <h2 class="section-heading">Wawasan <em>Kesejahteraan.</em></h2>
                </div>
                <a href="{{ route('public.articles.index') }}" class="btn-text-link">
                    Lihat Semua
                    <span class="material-symbols-outlined"
                        style="font-size:16px;vertical-align:middle;">arrow_forward</span>
                </a>
            </div>

            <div class="articles-grid">
                @forelse($articles as $article)
                    <article class="article-card">
                        <a href="{{ route('public.articles.show', $article->slug) }}" class="article-img-wrap">
                            <img src="{{ $article->thumbnail ? asset('storage/' . $article->thumbnail) : asset('assets/img/tim-kenanga.jpg') }}"
                                onerror="this.onerror=null; this.src='{{ asset('assets/img/tim-kenanga.jpg') }}'"
                                alt="{{ $article->title }}" loading="lazy">
                            <div class="article-img-overlay"></div>
                        </a>
                        <div class="article-body">
                            <h3 class="article-title">
                                <a href="{{ route('public.articles.show', $article->slug) }}">{{ $article->title }}</a>
                            </h3>
                            <div class="article-footer">
                                <span
                                    class="article-date">{{ \Carbon\Carbon::parse($article->published_at)->translatedFormat('d M Y') }}</span>
                                <div class="article-arrow">
                                    <span class="material-symbols-outlined">arrow_forward</span>
                                </div>
                            </div>
                        </div>
                    </article>
                @empty
                    <div style="grid-column:1/-1;">
                        <div class="empty-state">
                            <div class="empty-icon">
                                <span class="material-symbols-outlined">article</span>
                            </div>
                            <h3 class="empty-title">Belum Ada Artikel</h3>
                            <p class="empty-desc">Belum ada artikel kesehatan yang diterbitkan.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </section>


        {{-- ══ CTA ══ --}}
        <div class="cta-section" id="cta-section">
            <div class="cta-glow-1"></div>
            <div class="cta-glow-2"></div>
            <div class="cta-dots"></div>
            <div class="cta-inner">
                <h2 class="cta-heading">
                    Membangun Masa Depan <br><span class="highlight">Warga Sehat Sejahtera.</span>
                </h2>
                <p class="cta-desc">
                    Ada pertanyaan seputar program kesehatan posyandu atau butuh panduan pendaftaran? Hubungi tim pelayanan
                    kami.
                </p>
                <a href="{{ route('public.contact') }}" class="btn-cta">
                    Hubungi Pelayanan Kami
                </a>
            </div>
        </div>

    </div>

    {{-- ══ GSAP ANIMATIONS ══ --}}
    <script>
        function initGsapAnimations() {
            if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;

            gsap.registerPlugin(ScrollTrigger);

            // ── PERBAIKAN UTAMA ──
            // Set semua elemen animasi ke visible dulu SEBELUM gsap.fromTo() dijalankan.
            // Ini mencegah elemen "hilang permanen" jika ScrollTrigger gagal trigger
            // (misal: elemen sudah di viewport saat load, font belum render, dll).
            var animatedSelectors = [
                "#fitur-section .feature-card",
                "#jadwal-grid > *",
                "#artikel-section .article-card",
                "#cta-section .cta-inner > *"
            ];
            animatedSelectors.forEach(function(sel) {
                document.querySelectorAll(sel).forEach(function(el) {
                    el.style.opacity = "1";
                    el.style.transform = "none";
                    el.style.visibility = "visible";
                });
            });

            // Defer by 1 frame so browser has painted layout before
            // ScrollTrigger calculates offsets.
            requestAnimationFrame(function() {
                ScrollTrigger.refresh();

                // ── Hero text — entrance only (no scroll trigger needed) ──
                gsap.from("#hero-text > *", {
                    duration: 1,
                    y: 32,
                    opacity: 0,
                    stagger: 0.12,
                    ease: "power3.out"
                });

                // ── Hero visual ──
                gsap.from("#hero-images", {
                    duration: 1.2,
                    y: 20,
                    opacity: 0,
                    ease: "power3.out",
                    delay: 0.25
                });

                // ── Feature cards ──
                if (document.querySelector("#fitur-section .feature-card")) {
                    gsap.fromTo(
                        "#fitur-section .feature-card", {
                            y: 36,
                            opacity: 0
                        }, {
                            scrollTrigger: {
                                trigger: "#fitur-section",
                                start: "top 85%",
                                once: true,
                                invalidateOnRefresh: true,
                            },
                            duration: 0.7,
                            y: 0,
                            opacity: 1,
                            stagger: 0.1,
                            ease: "power2.out"
                        }
                    );
                }

                // ── Schedule cards ──
                if (document.querySelector("#jadwal-grid")) {
                    gsap.fromTo(
                        "#jadwal-grid > *", {
                            y: 32,
                            opacity: 0
                        }, {
                            scrollTrigger: {
                                trigger: "#jadwal",
                                start: "top 85%",
                                once: true,
                                invalidateOnRefresh: true,
                            },
                            duration: 0.8,
                            y: 0,
                            opacity: 1,
                            stagger: 0.12,
                            ease: "power2.out"
                        }
                    );
                }

                // ── Article cards ──
                // Pakai fromTo agar state awal & akhir eksplisit.
                // once:true  → tidak reset saat scroll balik ke atas.
                // invalidateOnRefresh:true → recalculate posisi trigger setelah
                //   resize / font load selesai, cegah misfiring karena layout shift.
                if (document.querySelector("#artikel-section .article-card")) {
                    gsap.fromTo(
                        "#artikel-section .article-card", {
                            y: 28,
                            opacity: 0
                        }, {
                            scrollTrigger: {
                                trigger: "#artikel-section",
                                start: "top 90%",
                                once: true,
                                invalidateOnRefresh: true,
                            },
                            duration: 0.7,
                            y: 0,
                            opacity: 1,
                            stagger: 0.1,
                            ease: "power2.out"
                        }
                    );
                }

                // ── CTA ──
                if (document.querySelector("#cta-section")) {
                    gsap.fromTo(
                        "#cta-section .cta-inner > *", {
                            y: 28,
                            opacity: 0
                        }, {
                            scrollTrigger: {
                                trigger: "#cta-section",
                                start: "top 90%",
                                once: true,
                                invalidateOnRefresh: true,
                            },
                            duration: 0.9,
                            y: 0,
                            opacity: 1,
                            stagger: 0.12,
                            ease: "power3.out"
                        }
                    );
                }
            });
        }

        // Run after window fully loaded (CSS, fonts, images all applied).
        // Also handle the case where the page is already fully loaded
        // (browser back navigation or very fast CDN cache hit).
        if (document.readyState === "complete") {
            initGsapAnimations();
        } else {
            window.addEventListener("load", initGsapAnimations, {
                once: true
            });
        }

        // Refresh setelah web fonts settle untuk mencegah typographic layout shift
        // yang bisa merusak kalkulasi posisi ScrollTrigger.
        if (document.fonts && document.fonts.ready) {
            document.fonts.ready.then(function() {
                if (window.ScrollTrigger) {
                    requestAnimationFrame(function() {
                        ScrollTrigger.refresh();
                    });
                }
            });
        }
    </script>
@endsection

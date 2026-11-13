<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Pass Fidélité VIP - {{ $card->customer_name }} | {{ $card->client->company_name ?? 'Fidélité' }}</title>
    
    <!-- PWA Web App Manifest & Mobile Meta -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#5736e9">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="VIP Pass">
    <link rel="apple-touch-icon" href="{{ asset('images/icon-192.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --brand-primary: #5736e9;
            --brand-gradient: linear-gradient(135deg, #7c3aed 0%, #5736e9 50%, #4338ca 100%);
            --card-gradient: linear-gradient(145deg, #160e33 0%, #22134e 45%, #311868 100%);
            --accent-gold: #fbbf24;
            --accent-green: #10b981;
            --bg-dark: #0a0614;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg-dark);
            color: #ffffff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 16px 12px;
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(87, 54, 233, 0.22) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(124, 58, 237, 0.18) 0%, transparent 45%);
            background-attachment: fixed;
        }

        .pass-container {
            width: 100%;
            max-width: 410px;
            margin: 0 auto;
            position: relative;
        }

        /* Apple Wallet / Futuristic VIP Card */
        .vip-card {
            background: var(--card-gradient);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 28px;
            box-shadow: 
                0 25px 50px -12px rgba(0, 0, 0, 0.6),
                0 0 40px rgba(87, 54, 233, 0.25);
            padding: 24px;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(16px);
        }

        /* Ambient Glow Effect */
        .vip-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at center, rgba(139, 92, 246, 0.12) 0%, transparent 60%);
            pointer-events: none;
            z-index: 0;
        }

        .card-content {
            position: relative;
            z-index: 1;
        }

        /* Top Bar */
        .card-header-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 14px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .brand-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-logo {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            color: var(--brand-primary);
            font-size: 1.1rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .brand-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .brand-name {
            font-size: 0.95rem;
            font-weight: 700;
            color: #ffffff;
            line-height: 1.2;
        }

        .brand-sub {
            font-size: 0.7rem;
            color: rgba(255, 255, 255, 0.55);
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }

        .card-code-pill {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.72rem;
            background: rgba(255, 255, 255, 0.12);
            color: #ffffff;
            padding: 4px 10px;
            border-radius: 100px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            letter-spacing: 0.5px;
        }

        /* Member Info */
        .member-block {
            text-align: center;
            margin-bottom: 22px;
        }

        .member-label {
            font-size: 0.7rem;
            color: rgba(255, 255, 255, 0.5);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .member-name {
            font-size: 1.45rem;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.5px;
        }

        .vip-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(87, 54, 233, 0.35);
            border: 1px solid rgba(139, 92, 246, 0.5);
            color: #c4b5fd;
            font-size: 0.72rem;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 100px;
            margin-top: 6px;
        }

        /* Stamp Card Block */
        .stamps-section {
            background: rgba(0, 0, 0, 0.35);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            padding: 16px;
            margin-bottom: 20px;
        }

        .stamps-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .stamps-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.7);
        }

        .stamps-counter {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.95rem;
            font-weight: 700;
            color: #ffffff;
            background: rgba(255, 255, 255, 0.1);
            padding: 2px 10px;
            border-radius: 8px;
        }

        .stamps-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
            margin-bottom: 14px;
        }

        .stamp-circle {
            aspect-ratio: 1;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            font-weight: 700;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
        }

        .stamp-circle.earned {
            background: var(--brand-gradient);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(87, 54, 233, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .stamp-circle.empty {
            background: rgba(255, 255, 255, 0.06);
            color: rgba(255, 255, 255, 0.35);
            border: 1.5px dashed rgba(255, 255, 255, 0.2);
        }

        /* Progress Bar */
        .progress-bar-container {
            width: 100%;
            height: 8px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 100px;
            overflow: hidden;
            margin-bottom: 10px;
        }

        .progress-bar-fill {
            height: 100%;
            border-radius: 100px;
            background: linear-gradient(90deg, #10b981 0%, #34d399 100%);
            transition: width 0.6s ease;
        }

        .reward-info {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.85);
            text-align: center;
        }

        .reward-info strong {
            color: var(--accent-gold);
        }

        /* Reward Banner if available */
        .reward-unlocked-banner {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border-radius: 16px;
            padding: 14px;
            text-align: center;
            margin-bottom: 20px;
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.35);
            animation: pulse-glow 2s infinite;
        }

        @keyframes pulse-glow {
            0%, 100% { transform: scale(1); box-shadow: 0 8px 20px rgba(16, 185, 129, 0.35); }
            50% { transform: scale(1.02); box-shadow: 0 12px 28px rgba(16, 185, 129, 0.55); }
        }

        .reward-unlocked-title {
            font-size: 0.95rem;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 2px;
        }

        .reward-unlocked-desc {
            font-size: 0.78rem;
            color: rgba(255, 255, 255, 0.9);
        }

        /* QR Code Presentation Box */
        .qr-box {
            background: #ffffff;
            border-radius: 20px;
            padding: 16px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            margin-bottom: 16px;
        }

        .qr-box svg {
            width: 100%;
            max-width: 190px;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        .qr-helper {
            font-size: 0.75rem;
            color: #6b7280;
            margin-top: 8px;
            font-weight: 600;
        }

        /* Footer Chips */
        .pass-footer {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            padding-top: 14px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 0.72rem;
            color: rgba(255, 255, 255, 0.55);
        }

        .footer-chip {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* Instructions card underneath */
        .instructions-card {
            margin-top: 16px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 18px;
            padding: 14px 18px;
            text-align: center;
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.6);
        }

        .instructions-card strong {
            color: #ffffff;
        }
    </style>
</head>
<body>

    <div class="pass-container">

        <!-- VIP PASS CARD -->
        <div class="vip-card">
            <div class="card-content">
                
                <!-- Card Header -->
                <div class="card-header-bar">
                    <div class="brand-info">
                        <div class="brand-logo">
                            @if($card->client->logo)
                                <img src="{{ asset('storage/' . $card->client->logo) }}" alt="Logo">
                            @else
                                {{ substr($card->client->company_name ?? 'C', 0, 1) }}
                            @endif
                        </div>
                        <div>
                            <div class="brand-name">{{ $card->client->company_name ?? 'Fidélité' }}</div>
                            <div class="brand-sub">Carte Membre Officielle</div>
                        </div>
                    </div>
                    <div class="card-code-pill">
                        {{ $card->card_code }}
                    </div>
                </div>

                <!-- Member Name & Tier -->
                <div class="member-block">
                    <div class="member-label">Titulaire du Pass</div>
                    <h1 class="member-name">{{ $card->customer_name }}</h1>
                    <div class="vip-badge">
                        <i class="bi bi-patch-check-fill text-warning"></i>
                        <span>Membre VIP &bull; {{ $card->card_type === 'nfc' ? 'Puce NFC' : ($card->card_type === 'both' ? 'QR & NFC' : 'Pass Digital') }}</span>
                    </div>
                </div>

                @php
                    $settings = $card->client->getLoyaltyProgramSetting();
                    $discountValue = $settings->calculateDiscountForPoints($card->points_balance);
                @endphp

                <!-- Points Balance Box -->
                <div class="points-card-block" style="background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.12); border-radius: 20px; padding: 16px; margin-bottom: 16px;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <span style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 1px; color: rgba(255,255,255,0.6); font-weight: 700; display: block;">Solde de Points</span>
                            <div style="display: flex; align-items: baseline; gap: 6px; margin-top: 2px;">
                                <span style="font-size: 2rem; font-weight: 800; font-family: 'JetBrains Mono', monospace; color: #ffffff;">{{ number_format($card->points_balance) }}</span>
                                <span style="font-size: 0.85rem; color: #c4b5fd; font-weight: 600;">pts</span>
                            </div>
                        </div>
                        <div style="text-align: right; background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 12px; padding: 8px 12px;">
                            <small style="font-size: 0.65rem; color: #a7f3d0; text-transform: uppercase; font-weight: 700; display: block;">Valeur en Remise</small>
                            <span style="font-size: 1.15rem; font-weight: 800; color: #10b981; font-family: 'JetBrains Mono', monospace;">{{ $discountValue }} {{ $settings->currency }}</span>
                        </div>
                    </div>
                </div>

                <!-- Reward Unlocked Banner (if threshold is met) -->
                @if($card->isRewardAvailable())
                    <div class="reward-unlocked-banner">
                        <div class="reward-unlocked-title">
                            <i class="bi bi-gift-fill me-1"></i> Récompense Débloquée !
                        </div>
                        <div class="reward-unlocked-desc">
                            Présentez ce pass en caisse pour obtenir : <strong>{{ $card->reward_text }}</strong>
                        </div>
                    </div>
                @endif

                <!-- Stamps Collection Grid -->
                <div class="stamps-section">
                    <div class="stamps-header">
                        <span class="stamps-title">Mes Tampons</span>
                        <span class="stamps-counter">{{ $card->stamps_count }} / {{ $card->max_stamps }}</span>
                    </div>

                    <div class="stamps-grid">
                        @for($i = 1; $i <= $card->max_stamps; $i++)
                            <div class="stamp-circle {{ $i <= $card->stamps_count ? 'earned' : 'empty' }}">
                                @if($i <= $card->stamps_count)
                                    <i class="bi bi-check-lg"></i>
                                @else
                                    {{ $i }}
                                @endif
                            </div>
                        @endfor
                    </div>

                    <div class="progress-bar-container">
                        <div class="progress-bar-fill" style="width: {{ $card->progressPercentage() }}%;"></div>
                    </div>

                    <div class="reward-info">
                        @if($card->isRewardAvailable())
                            <span class="text-success fw-bold"><i class="bi bi-stars me-1"></i>Félicitations ! Récompense disponible en caisse.</span>
                        @else
                            <i class="bi bi-gift me-1 text-warning"></i>
                            <span>Plus que <strong>{{ $card->remainingStampsForReward() }}</strong> tampon(s) pour : <strong>{{ $card->reward_text }}</strong></span>
                        @endif
                    </div>
                </div>

                <!-- Merchant QR Code Scanner Box -->
                <div class="qr-box">
                    {!! $qrSvg !!}
                    <div class="qr-helper">
                        <i class="bi bi-qr-code-scan me-1"></i> Présentez ce code en caisse pour tamponner
                    </div>
                </div>

                <!-- Card Bottom Telemetry -->
                <div class="pass-footer">
                    @if($card->nfc_uid || $card->card_type !== 'qr_code')
                        <div class="footer-chip">
                            <i class="bi bi-broadcast text-warning"></i>
                            <span>NFC Touch Ready</span>
                        </div>
                    @endif
                    <div class="footer-chip">
                        <i class="bi bi-shield-check text-success"></i>
                        <span>Pass Vérifié</span>
                    </div>
                    @if($card->rewards_redeemed_count > 0)
                        <div class="footer-chip">
                            <i class="bi bi-trophy text-warning"></i>
                            <span>{{ $card->rewards_redeemed_count }} cadeau(x) obtenu(s)</span>
                        </div>
                    @endif
                </div>

            </div>
        </div>

        <!-- Add to Home Screen Hint -->
        <div class="instructions-card">
            <i class="bi bi-phone me-1 text-purple"></i>
            <span>Pour un accès instantané en 1 clic : ouvrez le menu de votre navigateur et appuyez sur <strong>« Ajouter à l'écran d'accueil »</strong>.</span>
        </div>

    </div>

    <script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/sw.js').catch(() => {});
        });
    }
    </script>
</body>
</html>


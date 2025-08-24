<!-- Welcome Section -->
<div class="welcome-section">
    <h1 class="welcome-title">مرحباً بك في خليليو</h1>
    <p class="welcome-subtitle">منصتك التعليمية الشاملة للامتحانات والدروس والملفات</p>
    <div class="user-info">
        <i class="fas fa-user"></i>
        <span>{{ $userName ?? 'المستخدم' }}</span>
    </div>
    
    <!-- ADD THIS BUTTON -->
    <div class="dosyat-button-container" style="margin-top: 2rem;">
        <a href="{{ route('dosyat.index') }}" class="btn-dosyat">
            <i class="fas fa-book"></i>
            <span>الدوسيات</span>
        </a>
    </div>
</div>
<!-- includes/lessons-grid.blade.php -->
<div class="lessons-grid">
    <?php $__currentLoopData = $lessons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lesson): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="lesson-card" data-lesson-id="<?php echo e($lesson->id); ?>">
            <div class="lesson-thumbnail">
                <?php if($lesson->isValidYoutubeUrl()): ?>
                    <img src="<?php echo e($lesson->youtube_thumbnail); ?>" alt="<?php echo e($lesson->name); ?>" class="thumbnail-image">
                    <div class="play-overlay">
                        <i class="fas fa-play"></i>
                    </div>
                <?php else: ?>
                    <div class="invalid-video">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>رابط غير صحيح</span>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="lesson-info">
                <h3 class="lesson-name"><?php echo e($lesson->name); ?></h3>
                <div class="lesson-meta">
                  
                    <span class="lesson-date">
                        <i class="fas fa-calendar"></i>
                        <?php echo e($lesson->formatted_date); ?>

                    </span>
                </div>
            </div>
            
            <?php if($lesson->isValidYoutubeUrl()): ?>
                <div class="lesson-actions">
                   
                    <a href="<?php echo e($lesson->watch_url); ?>" target="_blank" class="btn-lesson-action btn-youtube">
                        <i class="fab fa-youtube"></i>
                        يوتيوب
                    </a>
                </div>
            <?php else: ?>
                <div class="lesson-actions">
                    <button class="btn-lesson-action btn-disabled" disabled>
                        <i class="fas fa-times"></i>
                        غير متاح
                    </button>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<!-- Video Modal -->
<div id="videoModal" class="video-modal">
    <div class="video-modal-content">
        <div class="video-modal-header">
            <h3 id="videoTitle">عنوان الدرس</h3>
            <button class="video-modal-close">&times;</button>
        </div>
        <div class="video-container">
            <iframe id="videoIframe" src="" frameborder="0" allowfullscreen></iframe>
        </div>
    </div>
</div>

<script>
// Video modal functionality
function openVideo(embedUrl, title) {
    const modal = document.getElementById('videoModal');
    const iframe = document.getElementById('videoIframe');
    const titleElement = document.getElementById('videoTitle');
    
    iframe.src = embedUrl;
    titleElement.textContent = title;
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeVideo() {
    const modal = document.getElementById('videoModal');
    const iframe = document.getElementById('videoIframe');
    
    iframe.src = '';
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Close modal events
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('videoModal');
    const closeBtn = document.querySelector('.video-modal-close');
    
    closeBtn.addEventListener('click', closeVideo);
    
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeVideo();
        }
    });
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeVideo();
        }
    });
});
</script><?php /**PATH C:\xampp\htdocs\platform\resources\views/includes/lessons-grid.blade.php ENDPATH**/ ?>
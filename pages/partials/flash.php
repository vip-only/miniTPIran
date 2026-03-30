<?php if (!empty($flashMessage)): ?>
    <div class="alert alert-info" role="status">
        <?= htmlspecialchars((string) $flashMessage, ENT_QUOTES, 'UTF-8'); ?>
    </div>
<?php endif; ?>

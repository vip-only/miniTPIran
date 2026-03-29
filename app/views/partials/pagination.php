<?php if (!empty($pagination) && !empty($pagination['totalPages']) && $pagination['totalPages'] > 1): ?>
    <nav aria-label="Pagination">
        <ul class="pagination">
            <?php for ($i = 1; $i <= (int) $pagination['totalPages']; $i++): ?>
                <li class="page-item <?= $i === (int) $pagination['currentPage'] ? 'active' : ''; ?>">
                    <a class="page-link" href="<?= htmlspecialchars($pagination['baseUrl'] . '?page=' . $i, ENT_QUOTES, 'UTF-8'); ?>"><?= $i; ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
<?php endif; ?>

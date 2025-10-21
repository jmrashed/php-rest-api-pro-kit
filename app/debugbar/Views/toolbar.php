<?php $data = $debugData ?? []; ?>
<div id="debugbar">
    <button id="debugbar-toggle">×</button>
    <div id="debugbar-tabs">
        <div class="debugbar-tab" data-tab="debugbar-meta">Meta (<?= $data['_meta']['execution_time'] ?? 0 ?>ms)</div>
        <?php if (isset($data['queries'])): ?>
        <div class="debugbar-tab" data-tab="debugbar-queries">Queries (<?= $data['queries']['total_queries'] ?? 0 ?>)</div>
        <?php endif; ?>
        <?php if (isset($data['messages'])): ?>
        <div class="debugbar-tab" data-tab="debugbar-messages">Messages (<?= count($data['messages']['messages'] ?? []) ?>)</div>
        <?php endif; ?>
        <div class="debugbar-tab" data-tab="debugbar-memory">Memory</div>
        <div class="debugbar-tab" data-tab="debugbar-request">Request</div>
    </div>

    <div id="debugbar-meta" class="debugbar-content">
        <strong>Execution Time:</strong> <span class="debugbar-time"><?= $data['_meta']['execution_time'] ?? 0 ?>ms</span><br>
        <strong>Memory Usage:</strong> <?= number_format(($data['_meta']['memory_usage'] ?? 0) / 1024, 2) ?>KB<br>
        <strong>Peak Memory:</strong> <?= number_format(($data['_meta']['peak_memory'] ?? 0) / 1024 / 1024, 2) ?>MB<br>
        <strong>Timestamp:</strong> <?= $data['_meta']['timestamp'] ?? '' ?>
    </div>

    <?php if (isset($data['queries'])): ?>
    <div id="debugbar-queries" class="debugbar-content">
        <strong>Total: <?= $data['queries']['total_queries'] ?? 0 ?> queries in <?= $data['queries']['total_time'] ?? 0 ?>ms</strong>
        <?php foreach ($data['queries']['queries'] ?? [] as $query): ?>
        <div class="debugbar-query">
            <div><strong>SQL:</strong> <?= htmlspecialchars($query['sql']) ?></div>
            <?php if (!empty($query['bindings'])): ?>
            <div><strong>Bindings:</strong> <?= htmlspecialchars(json_encode($query['bindings'])) ?></div>
            <?php endif; ?>
            <div><strong>Time:</strong> <span class="debugbar-time"><?= $query['time'] ?>ms</span></div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if (isset($data['messages'])): ?>
    <div id="debugbar-messages" class="debugbar-content">
        <?php foreach ($data['messages']['messages'] ?? [] as $msg): ?>
        <div class="debugbar-message <?= $msg['level'] ?>">
            [<?= $msg['formatted_time'] ?>] <?= htmlspecialchars($msg['message']) ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div id="debugbar-memory" class="debugbar-content">
        <strong>Current:</strong> <?= $data['memory']['current_formatted'] ?? '0B' ?><br>
        <strong>Peak:</strong> <?= $data['memory']['peak_formatted'] ?? '0B' ?>
    </div>

    <div id="debugbar-request" class="debugbar-content">
        <strong>Method:</strong> <?= $data['request']['method'] ?? 'N/A' ?><br>
        <strong>URI:</strong> <?= htmlspecialchars($data['request']['uri'] ?? '') ?><br>
        <strong>Headers:</strong><br>
        <?php foreach ($data['request']['headers'] ?? [] as $key => $value): ?>
        <div style="margin-left: 10px;"><?= htmlspecialchars($key) ?>: <?= htmlspecialchars($value) ?></div>
        <?php endforeach; ?>
    </div>
</div>

<link rel="stylesheet" href="/debugbar.css">
<script src="/debugbar.js"></script>
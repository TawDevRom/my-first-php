<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

require __DIR__ . '/db.php';

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($method === 'GET' && $path === '/api/notes') {
    $notes = $pdo->query("SELECT * FROM notes")->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($notes, JSON_UNESCAPED_UNICODE);
    exit;
}

if ($method === 'GET' && preg_match('#^/api/notes/(\d+)$#', $path, $matches)) {
    $id = (int) $matches[1];
    $stmt = $pdo->prepare("SELECT * FROM notes WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $note = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($note === false) {
        http_response_code(404);
        echo json_encode(['error' => 'Заметка не найдена'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    echo json_encode($note, JSON_UNESCAPED_UNICODE);
    exit;
}

if ($method === 'POST' && $path === '/api/notes') {
    $raw = file_get_contents('php://input');
    $input = json_decode($raw, true);

    $title = $input['title'] ?? '';
    $body = $input['body'] ?? '';

    if ($title === '') {
        http_response_code(400);
        echo json_encode(['error' => 'Поле "title" обязательно'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    $stmt = $pdo->prepare("INSERT INTO notes (title, body) VALUES (:title, :body)");
    $stmt->execute([
        ':title' => $title,
        ':body' => $body,
    ]);
    $id = $pdo->lastInsertId();
    $stmt = $pdo->prepare("SELECT * FROM notes WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $note = $stmt->fetch(PDO::FETCH_ASSOC);

    http_response_code(201);
    echo json_encode($note, JSON_UNESCAPED_UNICODE);
    exit;
}

//curl -X POST http://localhost:8000/api/notes -H "Content-Type:aplication/json" -d "{\"title\":\"Тестовая\",\"body\":\"Создано через POST\"}"
http_response_code(404);
echo json_encode(['error' => 'Not Found'], JSON_UNESCAPED_UNICODE);
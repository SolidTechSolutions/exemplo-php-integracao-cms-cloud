<?php
declare(strict_types=1);

/**
 * [EN]    CAdES (CMS) signing example — Cloud HSM / PSC (one-step).
 *         Setup: composer install && cp .env.example .env  (edit .env)
 *         Run:   php -S 0.0.0.0:8093 -t public
 *         Batch: POST http://localhost:8093/api/cms/sign-cloud
 *         Form:  POST http://localhost:8093/api/cms/sign/form
 *
 * [PT-BR] Exemplo de assinatura CAdES (CMS) — Cloud HSM / PSC (passo único).
 *         Configurar: composer install && cp .env.example .env  (editar .env)
 *         Executar:   php -S 0.0.0.0:8093 -t public
 */

require __DIR__ . '/../vendor/autoload.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use SolidSign\CmsCloudService;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();

$app     = AppFactory::create();
$service = new CmsCloudService();

// ── Batch endpoint ──────────────────────────────────────────────────────────
$app->post('/api/cms/sign-cloud', function (Request $request, Response $response) use ($service) {
    $inputPath  = $_ENV['SOLIDSIGN_BATCH_INPUT_PATH']  ?? '';
    $outputPath = $_ENV['SOLIDSIGN_BATCH_OUTPUT_PATH'] ?? '';

    if (!is_dir($inputPath)) {
        $response->getBody()->write(json_encode(['error' => "Invalid input path: $inputPath"]));
        return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
    }

    $zipPath = $service->signBatch($inputPath, $outputPath);
    if ($zipPath === null) {
        $response->getBody()->write(json_encode(['error' => 'Processing failed. Check logs.']));
        return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
    }

    $response->getBody()->write(json_encode(['message' => "Processing completed! ZIP generated at: $zipPath"]));
    return $response->withHeader('Content-Type', 'application/json');
});

// ── Form endpoint ───────────────────────────────────────────────────────────
$app->post('/api/cms/sign/form', function (Request $request, Response $response) use ($service) {
    $params        = (array) $request->getParsedBody();
    $uploadedFiles = $request->getUploadedFiles();

    $files = ['document' => []];
    $list  = $uploadedFiles['document'] ?? [];
    if (!is_array($list)) $list = [$list];
    foreach ($list as $uf) {
        if ($uf->getError() === UPLOAD_ERR_OK) {
            $files['document'][] = ['content' => (string) $uf->getStream(), 'filename' => $uf->getClientFilename()];
        }
    }

    $zipPath = $service->signForm($params, $files);
    if ($zipPath === null) {
        $response->getBody()->write(json_encode(['error' => 'Processing failed. Check logs.']));
        return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
    }

    $zipContent = (string) file_get_contents($zipPath);
    @unlink($zipPath);
    $response->getBody()->write($zipContent);
    return $response
        ->withHeader('Content-Type', 'application/zip')
        ->withHeader('Content-Disposition', 'attachment; filename="signed_cms.zip"');
});

$app->run();

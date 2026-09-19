# 🇧🇷 SolidSign API - Exemplo de Integração: Assinatura CAdES (CMS) com HSM/Nuvem (PHP)

## Requisitos

- PHP (built-in server) (`composer install && cp .env.example .env`)
- Um token JWT válido (`POST /solidsign/auth/token`)
- Front-end de referência (opcional): [`exemplo-react-cms-cloud`](https://github.com/SolidTechSolutions/exemplo-react-cms-cloud)

## Como rodar

```bash
composer install && cp .env.example .env
php -S 0.0.0.0:8093 -t public
```

O serviço sobe em `http://localhost:8093`.

## Como funciona

Este back-end expõe o endpoint abaixo, que recebe um formulário (`multipart/form-data`, CORS liberado) e repassa os dados pra SolidSign API real, devolvendo o resultado:

- `POST /api/cms/sign-cloud/form`

## Variáveis do formulário

| Campo | Significado | Default |
|---|---|---|
| `document[i]` | Documento(s) a assinar | — |
| `authorization` | Token JWT (Bearer) | — |
| `baseUrl` | URL base da SolidSign API | `https://www.solidsign.com.br` |
| `cloudCredentials` | Credenciais do HSM/PSC de nuvem (JSON: hsmUrl, hsmToken, uuidCert) | — |
| `profile` | Perfil de assinatura PBAD/ETSI | `ADRB` |
| `hashAlgorithm` | Algoritmo de hash | `SHA256` |
| `signaturePackaging` | Empacotamento CMS | `ATTACHED` |

---

# 🇬🇧 SolidSign API - Integration Example: CAdES (CMS) Signing with HSM/Cloud (PHP)

## Requirements

- PHP (built-in server) (`composer install && cp .env.example .env`)
- A valid JWT token (`POST /solidsign/auth/token`)
- Reference front-end (optional): [`exemplo-react-cms-cloud`](https://github.com/SolidTechSolutions/exemplo-react-cms-cloud)

## Running

```bash
composer install && cp .env.example .env
php -S 0.0.0.0:8093 -t public
```

The service starts on `http://localhost:8093`.

## How it works

This backend exposes the endpoint below, which accepts a form (`multipart/form-data`, CORS-enabled) and forwards the data to the real SolidSign API, returning the result:

- `POST /api/cms/sign-cloud/form`

## Form fields

| Field | Meaning | Default |
|---|---|---|
| `document[i]` | Document(s) to sign | — |
| `authorization` | JWT (Bearer) token | — |
| `baseUrl` | SolidSign API base URL | `https://www.solidsign.com.br` |
| `cloudCredentials` | Cloud HSM/PSC credentials (JSON: hsmUrl, hsmToken, uuidCert) | — |
| `profile` | PBAD/ETSI signature profile | `ADRB` |
| `hashAlgorithm` | Hash algorithm | `SHA256` |
| `signaturePackaging` | CMS packaging | `ATTACHED` |

<?php

require_once __DIR__ . '/../app/controle_acesso.php';

// Bloqueia o acesso se não estiver logado
exigirLogin();

require __DIR__ . '/../app/Views/painel_view.php';
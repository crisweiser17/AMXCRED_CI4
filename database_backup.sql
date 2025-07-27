-- MySQL dump 10.13  Distrib 8.0.40, for macos12.7 (arm64)
--
-- Host: localhost    Database: amxcred
-- ------------------------------------------------------
-- Server version	8.0.40

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clients` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `full_name` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `cpf` varchar(14) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `asaas_customer_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `birth_date` date NOT NULL,
  `occupation` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `industry` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Setor de atividade/indústria do cliente',
  `employment_duration` int DEFAULT NULL COMMENT 'em meses',
  `monthly_income` decimal(10,2) DEFAULT NULL,
  `pix_key_type` enum('cpf','email','phone','random') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pix_key` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `zip_code` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `street` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `number` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `complement` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `neighborhood` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `state` char(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `payslip_1` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'imagem',
  `payslip_2` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'imagem',
  `payslip_3` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'imagem',
  `id_front` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'imagem',
  `id_back` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'imagem',
  `selfie` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'imagem',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cpf` (`cpf`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clients`
--

LOCK TABLES `clients` WRITE;
/*!40000 ALTER TABLE `clients` DISABLE KEYS */;
INSERT INTO `clients` VALUES (1,'João Silva','123.456.789-09','joao.silva@example.com',NULL,'(11) 98765-4321','1990-05-15',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-07-27 17:16:20',NULL),(2,'Maria Santos','987.654.321-00','maria.santos@example.com',NULL,'(21) 99876-5432','1985-08-22',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-07-27 17:16:20',NULL),(3,'Pedro Oliveira','456.789.123-45','pedro.oliveira@example.com',NULL,'(31) 91234-5678','1992-03-10',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-07-27 17:16:20',NULL),(4,'Test Token','227.231.408-22','hello@crisweiser.com',NULL,'(19) 99898-9999','1900-12-31','empresario','servicos_financas',18,10000.00,'email','na@na.com','13405-235','Avenida Dona Lídia','1700','','Vila Rezende','Piracicaba','SP',NULL,NULL,NULL,'id_front.png','id_back.png','selfie.jpg','2025-07-27 17:26:38',NULL),(5,'João Ok','123.456.789-00','joao.ok@email.com',NULL,'(11) 99999-9999','1990-01-01',NULL,NULL,NULL,5000.00,NULL,NULL,'01234-567',NULL,NULL,NULL,'Centro','São Paulo','SP',NULL,NULL,NULL,'joao_ok_rg_frente.jpg','joao_ok_rg_verso.jpg','joao_ok_selfie.jpg','2025-07-27 17:29:33',NULL);
/*!40000 ALTER TABLE `clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cpf_consultation`
--

DROP TABLE IF EXISTS `cpf_consultation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cpf_consultation` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int unsigned NOT NULL,
  `raw_json` text COLLATE utf8mb4_general_ci COMMENT 'JSON completo retornado da API',
  `cpf_valido` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'CPF válido e existente na Receita Federal',
  `cpf_regular` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Situação regular (ativo)',
  `dados_divergentes` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Nome e data de nascimento divergem dos dados da API',
  `obito` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'CPF consta como falecido',
  `status` enum('pendente','aprovado','reprovado') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pendente',
  `motivo_reprovacao` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Motivo da reprovação: cpf_invalido, cpf_irregular, dados_divergentes, obito',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `ano_obito` int DEFAULT NULL COMMENT 'Ano do óbito se disponível',
  `codigo_erro` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Código de erro da API quando status = 0',
  `mensagem_erro` text COLLATE utf8mb4_general_ci COMMENT 'Mensagem de erro da API quando status = 0',
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`),
  CONSTRAINT `cpf_consultation_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cpf_consultation`
--

LOCK TABLES `cpf_consultation` WRITE;
/*!40000 ALTER TABLE `cpf_consultation` DISABLE KEYS */;
INSERT INTO `cpf_consultation` VALUES (1,4,'{\"status\":1,\"cpf\":\"227.231.408-22\",\"nome\":\"Test Token\",\"nascimento\":\"31\\/12\\/1900\",\"mae\":\"Maria Jose\",\"genero\":\"M\",\"situacaoInscricao\":\"anterior a 10\\/11\\/1990\",\"situacao\":\"Cancelada\",\"situacaoDigito\":\"03\",\"situacaoMotivo\":\"TITULAR FALECIDO\",\"situacaoAnoObito\":2015,\"situacaoComprovante\":\"1A1A.2B2B.3C3C.4D4D\",\"situacaoComprovanteEmissao\":\"27\\/07\\/2025 14:27:00\",\"situacaoComprovantePdf\":\"base64\",\"pacoteUsado\":9,\"saldo\":123,\"consultaID\":\"11bb22cc33dd44ee\",\"delay\":0.3}',1,0,1,1,'reprovado','CPF com situação irregular: Cancelada, Dados divergentes entre cliente e Receita Federal, Titular falecido (ano: 2015)','2025-07-27 17:27:00','2025-07-27 17:27:00',2015,NULL,NULL),(2,5,'{\"status\":1,\"cpf\":\"12345678900\",\"cpf_valido\":true,\"cpf_regular\":true,\"nome\":\"JOAO OK\",\"nascimento\":\"01\\/01\\/1990\",\"situacao\":\"REGULAR\",\"dados_divergentes\":false,\"obito\":false}',1,1,0,0,'aprovado',NULL,'2025-07-27 17:29:33','2025-07-27 17:29:33',NULL,NULL,NULL);
/*!40000 ALTER TABLE `cpf_consultation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `installments`
--

DROP TABLE IF EXISTS `installments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `installments` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `loan_id` int unsigned NOT NULL,
  `installment_number` int unsigned NOT NULL,
  `due_date` date NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('pending','paid','overdue','cancelled') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  `asaas_payment_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `paid_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `loan_id_installment_number` (`loan_id`,`installment_number`),
  KEY `loan_id` (`loan_id`),
  KEY `due_date` (`due_date`),
  KEY `status` (`status`),
  KEY `asaas_payment_id` (`asaas_payment_id`),
  CONSTRAINT `installments_loan_id_foreign` FOREIGN KEY (`loan_id`) REFERENCES `loans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `installments`
--

LOCK TABLES `installments` WRITE;
/*!40000 ALTER TABLE `installments` DISABLE KEYS */;
/*!40000 ALTER TABLE `installments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loan_plans`
--

DROP TABLE IF EXISTS `loan_plans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `loan_plans` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Nome comercial do plano. Ex: Plano Bronze, Empréstimo Rápido 500',
  `loan_amount` decimal(10,2) NOT NULL COMMENT 'O valor exato que será depositado na conta do cliente (o principal).',
  `total_repayment_amount` decimal(10,2) NOT NULL COMMENT 'A soma de tudo que o cliente deverá pagar (principal + todos os juros).',
  `number_of_installments` int NOT NULL COMMENT 'O número total de parcelas que serão geradas.',
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Flag para ativar/desativar o plano. Se FALSE, não deve aparecer na lista de seleção.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `is_active` (`is_active`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loan_plans`
--

LOCK TABLES `loan_plans` WRITE;
/*!40000 ALTER TABLE `loan_plans` DISABLE KEYS */;
INSERT INTO `loan_plans` VALUES (1,'Plano Bronze',500.00,1000.00,5,1,'2025-07-27 20:16:03','2025-07-27 20:25:00'),(2,'Plano Prata',1000.00,1320.00,6,1,'2025-07-27 20:16:03','2025-07-27 20:16:03'),(3,'Plano Ouro',2000.00,2800.00,8,1,'2025-07-27 20:16:03','2025-07-27 20:16:03'),(4,'Empréstimo Rápido 300',300.00,375.00,3,1,'2025-07-27 20:16:03','2025-07-27 20:16:03');
/*!40000 ALTER TABLE `loan_plans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loan_transactions`
--

DROP TABLE IF EXISTS `loan_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `loan_transactions` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `loan_id` int unsigned NOT NULL,
  `client_id` int unsigned NOT NULL,
  `type` enum('debit','credit') COLLATE utf8mb4_general_ci NOT NULL,
  `category` enum('loan_disbursement','installment_payment','fee','interest','penalty','refund') COLLATE utf8mb4_general_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `origin` enum('manual','asaas_webhook','system') COLLATE utf8mb4_general_ci NOT NULL,
  `related_installment_id` int unsigned DEFAULT NULL,
  `created_by_user_id` int unsigned DEFAULT NULL,
  `transaction_date` datetime NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `loan_transactions_created_by_user_id_foreign` (`created_by_user_id`),
  KEY `loan_id` (`loan_id`),
  KEY `client_id` (`client_id`),
  KEY `type` (`type`),
  KEY `category` (`category`),
  KEY `origin` (`origin`),
  KEY `related_installment_id` (`related_installment_id`),
  KEY `transaction_date` (`transaction_date`),
  CONSTRAINT `loan_transactions_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `loan_transactions_created_by_user_id_foreign` FOREIGN KEY (`created_by_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  CONSTRAINT `loan_transactions_loan_id_foreign` FOREIGN KEY (`loan_id`) REFERENCES `loans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `loan_transactions_related_installment_id_foreign` FOREIGN KEY (`related_installment_id`) REFERENCES `installments` (`id`) ON DELETE CASCADE ON UPDATE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loan_transactions`
--

LOCK TABLES `loan_transactions` WRITE;
/*!40000 ALTER TABLE `loan_transactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `loan_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loans`
--

DROP TABLE IF EXISTS `loans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `loans` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int unsigned NOT NULL,
  `loan_plan_id` int unsigned NOT NULL,
  `status` enum('pending_acceptance','accepted','pending_funding','funded','active','completed','cancelled','defaulted') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending_acceptance',
  `acceptance_token` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `token_expires_at` datetime DEFAULT NULL,
  `accepted_at` datetime DEFAULT NULL,
  `funded_at` datetime DEFAULT NULL,
  `funding_pix_transaction_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `funded_by_user_id` int unsigned DEFAULT NULL,
  `notes` text COLLATE utf8mb4_general_ci COMMENT 'Observações sobre o empréstimo',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `loans_funded_by_user_id_foreign` (`funded_by_user_id`),
  KEY `client_id` (`client_id`),
  KEY `loan_plan_id` (`loan_plan_id`),
  KEY `status` (`status`),
  KEY `acceptance_token` (`acceptance_token`),
  CONSTRAINT `loans_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `loans_funded_by_user_id_foreign` FOREIGN KEY (`funded_by_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  CONSTRAINT `loans_loan_plan_id_foreign` FOREIGN KEY (`loan_plan_id`) REFERENCES `loan_plans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loans`
--

LOCK TABLES `loans` WRITE;
/*!40000 ALTER TABLE `loans` DISABLE KEYS */;
INSERT INTO `loans` VALUES (1,5,1,'pending_acceptance','736a47968ed9d7bbebe4cd269e4a137b4a2f3f7868cc9147a42a6e76dc081fcf','2025-08-03 17:42:04',NULL,NULL,NULL,NULL,'xxx','2025-07-27 17:42:04','2025-07-27 17:42:04');
/*!40000 ALTER TABLE `loans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `version` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `class` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `group` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `namespace` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `time` int NOT NULL,
  `batch` int unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2025-07-21-222237','App\\Database\\Migrations\\CreateTestTable','default','App',1753210301,1),(16,'2025-07-22-184637','App\\Database\\Migrations\\CreateClientsTable','default','App',1753635974,2),(17,'2025-07-24-114901','App\\Database\\Migrations\\CreateSettingsTable','default','App',1753635974,2),(18,'2025-07-24-114906','App\\Database\\Migrations\\AddIndustryToClientsTable','default','App',1753635974,2),(19,'2025-07-25-130225','App\\Database\\Migrations\\CreateCpfConsultationTable','default','App',1753635974,2),(20,'2025-07-25-132025','App\\Database\\Migrations\\CreateRiskAnalysisTable','default','App',1753635974,2),(21,'2025-07-25-151250','App\\Database\\Migrations\\AddCpfApiSettings','default','App',1753635974,2),(22,'2025-07-25-183913','App\\Database\\Migrations\\AddErrorFieldsToCpfConsultationTable','default','App',1753635974,2),(23,'2025-07-26-191935','App\\Database\\Migrations\\CreateLoanPlansTable','default','App',1753635974,2),(24,'2025-07-27-122700','App\\Database\\Migrations\\CreateUsersTable','default','App',1753635974,2),(25,'2025-07-27-122735','App\\Database\\Migrations\\CreateLoansTable','default','App',1753635974,2),(26,'2025-07-27-122802','App\\Database\\Migrations\\CreateInstallmentsTable','default','App',1753635974,2),(27,'2025-07-27-122823','App\\Database\\Migrations\\CreateLoanTransactionsTable','default','App',1753635974,2),(28,'2025-07-27-122848','App\\Database\\Migrations\\AddAsaasCustomerIdToClients','default','App',1753635974,2),(29,'2025-07-27-123707','App\\Database\\Migrations\\CreateDefaultAdminUser','default','App',1753635974,2),(30,'2025-01-27-000000','App\\Database\\Migrations\\AddNotesToLoansTable','default','App',1753638257,3);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `risk_analysis`
--

DROP TABLE IF EXISTS `risk_analysis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `risk_analysis` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int unsigned NOT NULL,
  `dividas_bancarias` tinyint(1) DEFAULT NULL COMMENT 'Cliente possui dívidas bancárias',
  `cheque_sem_fundo` tinyint(1) DEFAULT NULL COMMENT 'Cliente possui histórico de cheque sem fundo',
  `protesto_nacional` tinyint(1) DEFAULT NULL COMMENT 'Cliente possui protestos nacionais',
  `score` int DEFAULT NULL COMMENT 'Score de crédito (0-1000)',
  `recomendacao_serasa` text COLLATE utf8mb4_general_ci COMMENT 'Recomendação textual do Serasa',
  `status` enum('pendente','consultado') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pendente',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`),
  CONSTRAINT `risk_analysis_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `risk_analysis`
--

LOCK TABLES `risk_analysis` WRITE;
/*!40000 ALTER TABLE `risk_analysis` DISABLE KEYS */;
/*!40000 ALTER TABLE `risk_analysis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(100) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Categoria da configuração (ex: client_required_fields, smtp_config, etc.)',
  `key` varchar(100) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Chave da configuração (ex: full_name, email, etc.)',
  `value` text COLLATE utf8mb4_general_ci COMMENT 'Valor da configuração',
  `description` text COLLATE utf8mb4_general_ci COMMENT 'Descrição da configuração',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `category_key` (`category`,`key`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'client_required_fields','full_name','true','Nome Completo (sempre obrigatório)',NULL,NULL),(2,'client_required_fields','cpf','true','CPF (sempre obrigatório)',NULL,NULL),(3,'client_required_fields','email','true','Email',NULL,NULL),(4,'client_required_fields','phone','true','Telefone',NULL,NULL),(5,'client_required_fields','birth_date','true','Data de Nascimento',NULL,NULL),(6,'client_required_fields','occupation','false','Ocupação',NULL,NULL),(7,'client_required_fields','industry','false','Indústria/Setor',NULL,NULL),(8,'client_required_fields','employment_duration','false','Tempo de Trabalho',NULL,NULL),(9,'client_required_fields','monthly_income','false','Renda Mensal',NULL,NULL),(10,'client_required_fields','pix_key_type','false','Tipo de Chave PIX',NULL,NULL),(11,'client_required_fields','pix_key','false','Chave PIX',NULL,NULL),(12,'client_required_fields','zip_code','false','CEP',NULL,NULL),(13,'client_required_fields','street','false','Rua',NULL,NULL),(14,'client_required_fields','number','false','Número',NULL,NULL),(15,'client_required_fields','complement','false','Complemento',NULL,NULL),(16,'client_required_fields','neighborhood','false','Bairro',NULL,NULL),(17,'client_required_fields','city','false','Cidade',NULL,NULL),(18,'client_required_fields','state','false','UF',NULL,NULL),(19,'client_required_fields','payslip_1','false','1º Comprovante de Renda',NULL,NULL),(20,'client_required_fields','payslip_2','false','2º Comprovante de Renda',NULL,NULL),(21,'client_required_fields','payslip_3','false','3º Comprovante de Renda',NULL,NULL),(22,'client_required_fields','id_front','false','RG Frente',NULL,NULL),(23,'client_required_fields','id_back','false','RG Verso',NULL,NULL),(24,'client_required_fields','selfie','false','Selfie',NULL,NULL),(25,'','cpf_api_environment','test','Ambiente da API CPF (test ou production)','2025-07-27 17:06:14',NULL),(26,'','cpf_api_test_token','5ae973d7a997af13f0aaf2bf60e65803','Token de teste da API CPF','2025-07-27 17:06:14',NULL),(27,'','cpf_api_production_token','','Token de produção da API CPF','2025-07-27 17:06:14',NULL),(28,'','cpf_api_test_url','https://api.cpfcnpj.com.br/test','URL base da API CPF para teste','2025-07-27 17:06:14',NULL),(29,'','cpf_api_production_url','https://api.cpfcnpj.com.br','URL base da API CPF para produção','2025-07-27 17:06:14',NULL);
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('admin','manager','operator') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'operator',
  `status` enum('active','inactive') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'active',
  `reset_token` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `reset_token_expires_at` datetime DEFAULT NULL,
  `last_login_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `status` (`status`),
  KEY `role` (`role`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Administrador','admin@amxcred.com','$2y$12$VPCCNXB63EtxDwGIEt0b1unTOUbShrgBs0T0gI8cb7kzj4y9EoD7O','admin','active',NULL,NULL,NULL,'2025-07-27 17:06:14','2025-07-27 17:06:14');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'amxcred'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-07-27 15:00:42

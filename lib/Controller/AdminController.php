<?php

namespace OCA\Libresign\Controller;

use OCA\Libresign\AppInfo\Application;
use OCA\Libresign\Service\AdminSignatureService;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\DataResponse;
use OCP\IRequest;

class AdminController extends Controller {
	use HandleErrorsTrait;
	use HandleParamsTrait;

	/** @var AdminSignatureService */
	private $adminSignatureservice;

	/** @var string */
	private $userId;

	public function __construct(
		IRequest $request,
		AdminSignatureService $adminSignatureService,
		$userId
	) {
		parent::__construct(Application::APP_ID, $request);
		$this->adminSignatureService = $adminSignatureService;
		$this->userId = $userId;
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function generateCertificate(
		string $commonName = null,
		string $country = null,
		string $organization = null,
		string $organizationUnit = null,
		string $cfsslUri = null,
		string $configPath = null
	): DataResponse {
		try {
			$this->checkParams([
				'commonName' => $commonName,
				'country' => $country,
				'organization' => $organization,
				'organizationUnit' => $organizationUnit,
				'cfsslUri' => $cfsslUri,
				'configPath' => $configPath
			]);

			$this->adminSignatureService->generate(
				$commonName,
				$country,
				$organization,
				$organizationUnit,
				$cfsslUri,
				$configPath
			);

			return new DataResponse([]);
		} catch (\Exception $exception) {
			return $this->handleErrors($exception);
		}
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function loadCertificate(): DataResponse {
		try {
			$certificate = $this->adminSignatureService->loadKeys();

			return new DataResponse($certificate);
		} catch (\Exception $exception) {
			return $this->handleErrors($exception);
		}
	}
}

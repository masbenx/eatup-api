<?php

class ApiController extends \BaseController {

	protected $statusCode = '200';

	public function getStatusCode() {
		return $this->statusCode;
	}

	/**
	 * @param mixed $statusCode
	 * @return $this
	 */
	public function setStatusCode($statusCode) {
		$this->statusCode = $statusCode;

		return $this;
	}

	/**
	 * @param string $message
	 * @return mixed
	 */
	public function respondNotFound($message = '') {
		return $this->setStatusCode(404)->respondWithError($message);
	}

	/**
	 * @param string $message
	 * @return mixed
	 */
	public function respondInternalError($message = 'Internal Error!') {
		return $this->setStatusCode(500)->respondWithError($message);
	}

	public function respond($data, $headers = []) {
		return Response::json($data, $this->getStatusCode(), $headers);
	}

	public function respondWithError($message) {
		return $this->respond([
			'status' => 'error',
			'data' => [
				'message' => $message,
				'error_code' => $this->getStatusCode()
			]
		]);
	}
}
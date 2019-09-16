<?php

namespace Platform\Dashboard\Repositories\Contracts;

interface DashboardRepository 
{
	/**
	 * Return model
	 *
	 * @return string
	 */
	public function model();

	/**
	 * Get notification list of user
	 *
	 * @return mixed
	 */
//	public function getNotification();

	/**
	 * Get Activity/News feed of any App
	 *
	 * @return mixed
	 */
//	public function getAppFeed();
}

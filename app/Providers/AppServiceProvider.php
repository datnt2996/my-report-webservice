<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\UserRepositoryInterface;
use App\Contracts\EventRepositoryInterface;
use App\Repositories\UserEloquentRepository;
use App\Repositories\EventEloquentRepository;
use App\Contracts\UserAddressRepositoryInterface;
use App\Repositories\UserAddressEloquentRepository;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->singleton(
			UserRepositoryInterface::class,
			UserEloquentRepository::class
		);
		$this->app->singleton(
			EventRepositoryInterface::class,
			EventEloquentRepository::class
		);
		$this->app->singleton(
			UserAddressRepositoryInterface::class,
			UserAddressEloquentRepository::class
		);
	}

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}
}
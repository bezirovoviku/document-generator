<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Request;

class GenerateRequest extends Job implements SelfHandling, ShouldQueue
{
	use InteractsWithQueue, SerializesModels;

	protected $request;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct(Request $request)
	{
		$this->request = $request;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle()
	{
		// exception = job is requeued
		$this->request->generate();
	}

	/**
	 * Handle a job failure.
	 *
	 * @return void
	 */
	public function failed()
	{
		$this->request->status = Request::STATUS_FAILED;
		$this->request->save();
	}
}

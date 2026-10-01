<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Personal Access Tokens') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Your personal access tokens are listed below. Use the "Create Token" button to generate a new token.') }}
        </p>
    </header>

     <x-splade-table :for="$tokens" />

     <x-splade-submit danger :label="__('Create Token')" />
</section>

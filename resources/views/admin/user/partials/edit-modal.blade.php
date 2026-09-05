<x-modal name="edit-modal" focusable>
    <form method="POST" :action="`/admin/dashboard/${selectedUser.id}`" class="p-6">
        @csrf
        @method('PATCH')

        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Edit User:') }} <span x-text="selectedUser.name"></span>
        </h2>

        <div class="mt-4 space-y-4">
            <div>
                <x-input-label for="edit_name" :value="__('Name')" />
                <x-text-input id="edit_name" name="name" type="text" class="mt-1 block w-full" x-model="selectedUser.name" required />
                <div class="validation-error-container">
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>
            </div>

            <div>
                <x-input-label for="edit_email" :value="__('Email')" />
                <x-text-input id="edit_email" name="email" type="email" class="mt-1 block w-full" x-model="selectedUser.email" required />
                <div class="validation-error-container">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
            </div>

            <div>
                <x-input-label for="edit_role" :value="__('Role')" />
                <select id="edit_role" name="role" x-model="selectedUser.role"
                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                    required>
                    <option value="client">{{ __('Client') }}</option>
                    <option value="admin">{{ __('Admin') }}</option>
                </select>
                <div class="validation-error-container">
                    <x-input-error :messages="$errors->get('role')" class="mt-2" />
                </div>
            </div>

            <div>
                <x-input-label for="edit_password" :value="__('New Password (Optional)')" />
                <x-text-input id="edit_password" name="password" type="password" class="mt-1 block w-full" x-model="selectedUser.password" placeholder="Leave blank to keep current password" />
                <div class="validation-error-container">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md">
                {{ __('Cancel') }}
            </button>

            <x-primary-button>
                {{ __('Update User') }}
            </x-primary-button>
        </div>
    </form>
</x-modal>
<?php

namespace App\Http\Requests;

use App\Models\PostCatalogue;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\CheckPostCatalogueChildrenRule;
class DeletePostCatalogueRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('id');
        return [
            'name' => [
                new CheckPostCatalogueChildrenRule($id)
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Bạn chưa nhập tên ngôn ngữ.',
            'name.string' => 'Tên ngôn ngữ phải là dạng kí tự.',
            'canonical.required' => 'Bạn chưa nhập từ khóa ngôn ngữ.',
            'canonical.unique' => 'Đường dẫn đã tồn tại hãy chọn từ khóa khác.',
        ];
    }
}

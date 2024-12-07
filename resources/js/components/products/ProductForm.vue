<script setup>
import { reactive, ref, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import axios from 'axios';

const router = useRouter();
const route = useRoute();

const isEditing = route.params.id ? true : false;
const currentStep = ref(1);

const form = reactive({
    name: '',
    description: '',
    images: [], 
    quantity: '',
    date_time: '',
    type: '',
    price: '',
    category: '',
    errors: {
        name: '',
        category: '',
        date_time: '',
        type: '',
        price: '',
        quantity: '',
        images: ''
    },
});

const validateStep1 = () => {
    let valid = true;
    if (!form.name) {
        form.errors.name = 'Name is required.';
        valid = false;
    } else {
        form.errors.name = '';
    }

    if (!form.category) {
        form.errors.category = 'Category is required.';
        valid = false;
    } else {
        form.errors.category = '';
    }

    return valid;
};

const validateStep2 = () => {

    // if (!form.images || form.images.length === 0) {
    //     form.errors.images = 'At least one image is required.';
    //     return false;
    // } else {
    //     form.errors.images = '';
    // }
    return true;
};

const validateStep3 = () => {
    let valid = true;

    if (!form.date_time) {
        form.errors.date_time = 'Date and Time is required.';
        valid = false;
    } else {
        form.errors.date_time = '';
    }

    if (!form.type) {
        form.errors.type = 'Type is required.';
        valid = false;
    } else {
        form.errors.type = '';
    }

    if (!form.quantity) {
        form.errors.quantity = 'Quantity is required.';
        valid = false;
    } else {
        form.errors.quantity = '';
    }

    if (!form.price) {
        form.errors.price = 'Price is required.';
        valid = false;
    } else {
        form.errors.price = '';
    }

    return valid;
};

onMounted(() => {
    if (isEditing) {
        axios.get(`/products/${route.params.id}`)
            .then(response => {
                const p = response.data;
                form.name = p.name;
                form.description = p.description || '';
                form.date_time = p.date_time ? p.date_time.replace(' ', 'T') : '';
                form.type = p.type;
                form.quantity = p.quantity;
                form.price = p.price;
                form.category = p.category || '';
          
                if (p.images && Array.isArray(p.images)) {
                   
                    
                    form.images = p.images.map(img => img);
                }
            });
    }
});

const goNext = () => {
    if (currentStep.value === 1) {
        if (!validateStep1()) return;
        currentStep.value = 2;
    } else if (currentStep.value === 2) {
        if (!validateStep2()) return;
        currentStep.value = 3;
    }
};

const goBack = () => {
    if (currentStep.value > 1) {
        currentStep.value -= 1;
    }
};

const handleSave = () => {
    if (!validateStep3()) return;

    const formData = new FormData();
    formData.append('name', form.name);
    formData.append('description', form.description);
    formData.append('date_time', form.date_time);
    formData.append('type', form.type);
    formData.append('quantity', form.quantity);
    formData.append('price', form.price);
    formData.append('category', form.category);

   
    form.images.forEach(img => {
        if (img instanceof File) {
            formData.append('images[]', img);
        }
    });

    const url = isEditing ? `/products/${route.params.id}` : '/products';
    axios.post(url, formData)
        .then(() => {
            toast.fire({
                icon: 'success',
                title: isEditing ? 'Product updated successfully.' : 'Product added successfully.'
            });
            router.push('/');
        })
        .catch((error) => {
            console.error(error);
            alert(isEditing ? 'Error updating product' : 'Error adding product');
        });
};

const handleFileChange = (e) => {
    const files = e.target.files;
    if (files) {

        const newFiles = Array.from(files);
        form.images = form.images.concat(newFiles);
    }
};

const removeImage = (index) => {
    form.images.splice(index, 1);
};

const getImagePreview = (img) => {
    if (img instanceof File) {
        return URL.createObjectURL(img);
    }

    return '/upload/' + img;
};
</script>

<template>
    <div class="products__create">
        <div class="products__create__titlebar dflex justify-content-between align-items-center">
            <div class="products__create__titlebar--item">
                <p class="title-product">{{ isEditing ? 'Edit Product' : 'Add Product' }}</p>
                
            </div>
       
        </div>
        <hr/>

   
        <div class="stepper my-3">
            <div :class="['step', currentStep === 1 ? 'active' : '']">1</div>
            <div :class="['step', currentStep === 2 ? 'active' : '']">2</div>
            <div :class="['step', currentStep === 3 ? 'active' : '']">3</div>
        </div>

        <!-- Step 1 -->
        <div v-if="currentStep === 1" class="card p-3 bg-white">
            <p>Name</p>
            <input type="text" class="input" v-model="form.name">
            <span v-if="form.errors.name" class="text-danger">{{ form.errors.name }}</span>

            <p class="my-2">Category</p>
            <select class="input" v-model="form.category">
                <option value="">-- Select Category --</option>
                <option value="Electronics">Electronics</option>
                <option value="Fashion">Fashion</option>
                <option value="Home">Home</option>
                <option value="Books">Books</option>
            </select>
            <span v-if="form.errors.category" class="text-danger">{{ form.errors.category }}</span>

            <p class="my-2">Description</p>
            <textarea cols="10" rows="5" class="textarea" v-model="form.description"></textarea>

            <div class="d-flex justify-content-between mt-3">
                <div></div>
                <button class="btn btn-secondary" @click="goNext">Next</button>
            </div>
        </div>

        <!-- Step 2 -->
        <div v-if="currentStep === 2" class="card p-3 bg-white">
            <p>Images (can select multiple)</p>
            <input type="file" multiple @change="handleFileChange" class="form-control mt-2" />
            <span v-if="form.errors.images" class="text-danger">{{ form.errors.images }}</span>

            <!-- Preview all images with delete buttons -->
            <div class="mt-2 d-flex flex-wrap gap-2">
                <div v-for="(img, index) in form.images" :key="index" style="position: relative; display: inline-block;">
                    <img :src="getImagePreview(img)" style="height:100px;"/>
                    <button @click="removeImage(index)" 
                            style="position: absolute; top:0; right:0; background:red; color:#fff; border:none; border-radius:50%; width:20px; height:20px; cursor:pointer;">
                        X
                    </button>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-3">
                <button class="btn btn-secondary" @click="goBack">Back</button>
                <button class="btn btn-secondary" @click="goNext">Next</button>
            </div>
        </div>

        <!-- Step 3 -->
        <div v-if="currentStep === 3" class="card p-3 bg-white">
            <p>Date & Time</p>
            <input type="datetime-local" class="input" v-model="form.date_time">
            <span v-if="form.errors.date_time" class="text-danger">{{ form.errors.date_time }}</span>

            <p class="my-2">Type</p>
            <select type="text" class="input" v-model="form.type">
                <option value="">-- Select Type --</option>
                <option value="Physical">Physical</option>
                <option value="Digital">Digital</option>
            </select>
            <span v-if="form.errors.type" class="text-danger">{{ form.errors.type }}</span>

            <p class="my-2">Quantity</p>
            <input type="number" class="input" v-model="form.quantity">
            <span v-if="form.errors.quantity" class="text-danger">{{ form.errors.quantity }}</span>

            <p class="my-2">Price</p>
            <input type="number" step="0.01" class="input" v-model="form.price">
            <span v-if="form.errors.price" class="text-danger">{{ form.errors.price }}</span>

            <div class="d-flex justify-content-between mt-3">
                <button class="btn btn-secondary" @click="goBack">Back</button>
                <button class="btn btn-success" @click="handleSave">{{ isEditing ? 'Update' : 'Submit' }}</button>
            </div>
        </div>
    </div>
</template>

<style scoped>
.text-danger {
    color: red;
    font-size: 0.9em;
}

.stepper {
    display: flex;
    gap: 20px;
    justify-content: center;
}

.step {
    background: #ccc;
    padding: 8px 15px;
    border-radius: 100px;
}

.step.active {
    background: #007bff;
    color: #fff;
}
</style>

<script setup>
import '@fortawesome/fontawesome-free/css/all.css';
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const router = useRouter();

const products = ref([]);
const search = ref('');
const category = ref('');
const currentPage = ref(1);
const lastPage = ref(1);

const categories = ['All', 'Electronics', 'Fashion', 'Home', 'Books'];

const fetchProducts = () => {
  axios.get('/products', {
    params: {
      search: search.value,
      category: category.value === 'All' ? '' : category.value,
      page: currentPage.value
    }
  }).then(response => {
    products.value = response.data.data;
    lastPage.value = response.data.last_page;
  });
};

onMounted(fetchProducts);

watch([search, category, currentPage], fetchProducts);

const newProduct = () => {
  router.push('/products/create');
};

const editProduct = (id) => {
  router.push(`/products/${id}/edit`);
};

const deleteProduct = (id) => {
  if (confirm('Are you sure you want to delete this product?')) {
    axios.delete(`/products/${id}`)
    .then(() => {
      fetchProducts();
      toast.fire({
        icon: 'success',
        title: 'Product deleted successfully.'
      });
    });
  }
};

const goToPage = (page) => {
  if (page >= 1 && page <= lastPage.value) {
    currentPage.value = page;
  }
};

// Modal logic
const showModal = ref(false);
const modalImages = ref([]);

const openModal = (images) => {
  modalImages.value = images || [];
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
};
</script>

<template>
    <div class="products__list table my-3">
   
        <div class="customers__titlebar d-flex justify-content-between align-items-center">
            <div class="customers__titlebar--item">
                <h1 class="title-product">Products</h1>
            </div>
           
        </div>
        <hr/>

        <div class="mt-3 d-flex justify-content-between">
  <input
    type="text"
    class="input mr-2"
    placeholder="Search by name or description"
    v-model="search"
    style="flex: 1; margin-right: 10px;"
  >
  
  <select
    class="input"
    v-model="category"
    style="flex: 1;"
  >

    <option disabled value="">Select Category</option>

    <option v-for="cat in categories" :key="cat" :value="cat">
      {{ cat }}
    </option>
  </select>
</div>



        <div class="table--heading mt-2 products__list__heading" style="padding-top: 20px;background:#FFF">
            <p class="table--heading--col1">image</p>
            <p class="table--heading--col2">Product</p>
            <p class="table--heading--col1">Description</p>

            <p class="table--heading--col3">Category</p>
            <p class="table--heading--col4">Date and Time</p>
            <p class="table--heading--col5">Actions</p>
        

        </div>

    
        <div v-for="product in products" :key="product.id" class="table--items products__list__item">
            <div class="products__list__item--imgWrapper" style="position: relative;" @click="openModal(product.images)">
                <img 
                    class="products__list__item--img" 
                    :src="(product.images && product.images.length > 0) ? '/upload/'+product.images[0] : '/upload/no-image.png'" 
                    style="height: 40px; cursor: pointer;"
                >
              
                <div v-if="product.images && product.images.length > 1" 
                     style="position: absolute; bottom: 0; right: 0; background: rgba(0,0,0,0.7); color: #fff; padding: 2px 5px; border-radius: 3px; font-size: 12px;">
                    +{{ product.images.length - 1 }}
                </div>
            </div>
            <a href="# " class="table--items--col2">{{ product.name }}</a>
            <p class="table--items--col2">{{ product.description }}</p>
            <p class="table--items--col3">{{ product.category }}</p>
            <p class="table--items--col3">{{ product.date_time }}</p>
            <div>
                <button class="btn-icon btn-icon-success" @click="editProduct(product.id)">
                    <i class="fas fa-pencil-alt"></i>
                  </button>
                  <button class="btn-icon btn-icon-danger" @click="deleteProduct(product.id)">
                    <i class="far fa-trash-alt"></i>
                </button>
            </div>
        </div>


        <div class="mt-3">
  <nav aria-label="Page navigation">
    <ul class="pagination justify-content-center">

      <li class="page-item" :class="{ disabled: currentPage === 1 }">
        <button class="page-link" :disabled="currentPage === 1" @click="goToPage(currentPage - 1)">
          Prev
        </button>
      </li>


      <li class="page-item disabled">
        <span class="page-link">
          Page {{ currentPage }} of {{ lastPage }}
        </span>
      </li>


      <li class="page-item" :class="{ disabled: currentPage === lastPage }">
        <button class="page-link" :disabled="currentPage === lastPage" @click="goToPage(currentPage + 1)">
          Next
        </button>
      </li>
    </ul>
  </nav>
</div>


   
        <div v-if="showModal" style="position: fixed; top:0; left:0; right:0; bottom:0; background: rgba(0,0,0,0.5); display: flex; align-items:center; justify-content:center; z-index:9999;">
            <div style="background: #fff; padding:20px; position: relative; max-width: 80%; max-height:80%; overflow:auto;">
                <button @click="closeModal" style="position: absolute; top:10px; right:10px; background:none; border:none; font-size:16px; cursor:pointer;">X</button>
                <h3>Product Images</h3>
                <div class="d-flex flex-wrap gap-2 mt-3">
                    <div v-for="(img, index) in modalImages" :key="index" style="margin: 5px;">
                        <img :src="'/upload/'+img" style="max-height:150px; max-width:150px; object-fit:cover;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

.btn-icon-success i {
    color: green;
}
.btn-icon-danger i {
    color: red;
}
</style>

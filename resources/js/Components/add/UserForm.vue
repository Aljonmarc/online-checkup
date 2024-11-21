<template>
    <el-dialog
      :visible="dialogVisible" 
      title="Add User"
      width="500"
      @close="handleClose" 
    >
      <!-- Form and other content here -->
    </el-dialog>
  </template>
  
  <script setup>
  import { ref, defineProps, defineEmits } from 'vue';
  
  const props = defineProps({
    dialogVisible: {
      type: Boolean,
      required: true,
    },
  });
  
  const emit = defineEmits(['update:dialogVisible']);
  
  const handleClose = () => {
    emit('update:dialogVisible', false); // Emit to update dialog visibility when closed
  };
  </script>
  
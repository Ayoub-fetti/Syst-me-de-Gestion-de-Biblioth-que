<?php
require_once 'connection.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
 <head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>
   Dashboard
  </title>
  <script src="https://cdn.tailwindcss.com">
  </script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <link href="<?php echo BASE_URL; ?>/public/css/style.css" rel="stylesheet">
 </head>
 <body class="bg-gray-100 font-sans antialiased">
  <div class="flex">
   <!-- Sidebar -->
   <div class="w-64 bg-blue-900 text-white min-h-screen">
    <div class="p-4 flex items-center">
     <img alt="Logo" class="w-10 h-10 rounded-full" src="<?php echo BASE_URL; ?>/public/images/logo.jpg"/>
     <span class="ml-3 text-xl font-semibold">
      Datta Able
     </span>
    </div>
    <nav class="mt-10">
     <a class="flex items-center p-3 bg-blue-800 rounded-lg" href="<?php echo BASE_URL; ?>/views/dashboard/admin.php">
      <i class="fas fa-tachometer-alt">
      </i>
      <span class="ml-3">
       Dashboard
      </span>
     </a>
     <div class="mt-5">
  
      <a class="flex items-center p-3 hover:bg-blue-800 rounded-lg" href="<?php echo BASE_URL; ?>/views/components/index.php">
       <i class="fas fa-cube">
       </i>
       <span class="ml-3">
        Components
       </span>
      </a>
     </div>
     <div class="mt-5">
      <a class="flex items-center p-3 hover:bg-blue-800 rounded-lg" href="<?php echo BASE_URL; ?>/views/forms/index.php">
       <i class="fas fa-edit">
       </i>
       <span class="ml-3">
        Form elements
       </span>
      </a>
      <a class="flex items-center p-3 hover:bg-blue-800 rounded-lg" href="<?php echo BASE_URL; ?>/views/tables/index.php">
       <i class="fas fa-table">
       </i>
       <span class="ml-3">
        Table
       </span>
      </a>
     </div>
     <div class="mt-5">
      <a class="flex items-center p-3 hover:bg-blue-800 rounded-lg" href="<?php echo BASE_URL; ?>/views/charts/index.php">
       <i class="fas fa-chart-bar">
       </i>
       <span class="ml-3">
        Chart
       </span>
      </a>
      <a class="flex items-center p-3 hover:bg-blue-800 rounded-lg" href="<?php echo BASE_URL; ?>/views/maps/index.php">
       <i class="fas fa-map">
       </i>
       <span class="ml-3">
        Maps
       </span>
      </a>
     </div>
     <div class="mt-5">
      <a class="flex items-center p-3 hover:bg-blue-800 rounded-lg" href="<?php echo BASE_URL; ?>/views/auth/login.php">
       <i class="fas fa-user">
       </i>
       <span class="ml-3">
        Authentication
       </span>
      </a>
      <a class="flex items-center p-3 hover:bg-blue-800 rounded-lg" href="<?php echo BASE_URL; ?>/views/sample/index.php">
       <i class="fas fa-file">
       </i>
       <span class="ml-3">
        Sample page
       </span>
      </a>
     </div>
    </nav>
   </div>
   <!-- Main Content -->
   <div class="flex-1 p-6">
    <div class="flex justify-between items-center mb-6">
     <h1 class="text-2xl font-semibold">
      Dashboard
     </h1>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
     <div class="bg-white p-4 rounded-lg shadow">
      <div class="flex items-center justify-between">
       <div>
        <p class="text-gray-600">
         Utilisateurs
        </p>
        <p class="text-2xl font-semibold text-green-500">
         4
        </p>
       </div>
       <i class="fas fa-user text-green-500"></i>
       </i>
      </div>
      <div class="mt-4">
       <div class="h-2 bg-green-500 rounded-full" style="width: 100%;">
       </div>
      </div>
     </div>
     <div class="bg-white p-4 rounded-lg shadow">
      <div class="flex items-center justify-between">
       <div>
        <p class="text-gray-600">
         Categories
        </p>
        <p class="text-2xl font-semibold text-green-500">
         120
        </p>
       </div>
      
       <i class="fas fa-bookmark text-green-500"></i>
       </i>
      </div>
      <div class="mt-4">
       <div class="h-2 bg-green-500 rounded-full" style="width: 100%;">
       </div>
      </div>
     </div>
     <div class="bg-white p-4 rounded-lg shadow">
      <div class="flex items-center justify-between">
       <div>
        <p class="text-gray-600">
         Livres réservé
        </p>
        <p class="text-2xl font-semibold text-green-500">
         30
        </p>
       </div>
     
       <i class="fas fa-book text-green-500"></i>
       </i>
      </div>
      <div class="mt-4">
       <div class="h-2 bg-green-500 rounded-full" style="width: 100%;">
       </div>
      </div>
     </div>
     <div class="bg-white p-4 rounded-lg shadow">
      <div class="flex items-center justify-between">
       <div>
        <p class="text-gray-600">
         Livres emprientés
        </p>
        <p class="text-2xl font-semibold text-green-500">
         12
        </p>
       </div>
       
       <i class="fas fa-book-open text-green-500"></i>
       </i>
      </div>
      <div class="mt-4">
       <div class="h-2 bg-green-500 rounded-full" style="width: 100%;">
       </div>
      </div>
     </div>


    </div>
    <div class="bg-white p-6 rounded-lg shadow mb-6">
     <h2 class="text-xl font-semibold mb-4">
      Recent Users
     </h2>
     <div class="space-y-4">
      <div class="flex items-center justify-between">
       <div class="flex items-center">
        <img alt="User avatar" class="w-10 h-10 rounded-full" height="40" src="https://storage.googleapis.com/a1aa/image/eNgDP2iQxBwfoEi8AuzfKGN6Ib4gheWm2BL3WqN14K4yNN4PB.jpg" width="40"/>
        <div class="ml-4">
         <p class="font-semibold">
          Isabella Christensen
         </p>
         <p class="text-gray-500 text-sm">
          Lorem Ipsum is simply...
         </p>
        </div>
       </div>
       <div class="flex items-center space-x-4">
        <p class="text-gray-500 text-sm">
         11 MAY 12:56
        </p>
        <button class="bg-red-500 text-white px-3 py-1 rounded-lg">
         Reject
        </button>
        <button class="bg-green-500 text-white px-3 py-1 rounded-lg">
         Approve
        </button>
       </div>
      </div>
      <div class="flex items-center justify-between">
       <div class="flex items-center">
        <img alt="User avatar" class="w-10 h-10 rounded-full" height="40" src="https://storage.googleapis.com/a1aa/image/eNgDP2iQxBwfoEi8AuzfKGN6Ib4gheWm2BL3WqN14K4yNN4PB.jpg" width="40"/>
        <div class="ml-4">
         <p class="font-semibold">
          Mathilde Andersen
         </p>
         <p class="text-gray-500 text-sm">
          Lorem Ipsum is simply...
         </p>
        </div>
       </div>
       <div class="flex items-center space-x-4">
        <p class="text-gray-500 text-sm">
         11 MAY 10:35
        </p>
        <button class="bg-red-500 text-white px-3 py-1 rounded-lg">
         Reject
        </button>
        <button class="bg-green-500 text-white px-3 py-1 rounded-lg">
         Approve
        </button>
       </div>
      </div>
      <div class="flex items-center justify-between">
       <div class="flex items-center">
        <img alt="User avatar" class="w-10 h-10 rounded-full" height="40" src="https://storage.googleapis.com/a1aa/image/eNgDP2iQxBwfoEi8AuzfKGN6Ib4gheWm2BL3WqN14K4yNN4PB.jpg" width="40"/>
        <div class="ml-4">
         <p class="font-semibold">
          Karla Sorensen
         </p>
         <p class="text-gray-500 text-sm">
          Lorem Ipsum is simply...
         </p>
        </div>
       </div>
       <div class="flex items-center space-x-4">
        <p class="text-gray-500 text-sm">
         9 MAY 17:38
        </p>
        <button class="bg-red-500 text-white px-3 py-1 rounded-lg">
         Reject
        </button>
        <button class="bg-green-500 text-white px-3 py-1 rounded-lg">
         Approve
        </button>
       </div>
      </div>
      <div class="flex items-center justify-between">
       <div class="flex items-center">
        <img alt="User avatar" class="w-10 h-10 rounded-full" height="40" src="https://storage.googleapis.com/a1aa/image/eNgDP2iQxBwfoEi8AuzfKGN6Ib4gheWm2BL3WqN14K4yNN4PB.jpg" width="40"/>
        <div class="ml-4">
         <p class="font-semibold">
          Ida Jorgensen
         </p>
         <p class="text-gray-500 text-sm">
          Lorem Ipsum is simply...
         </p>
        </div>
       </div>
       <div class="flex items-center space-x-4">
        <p class="text-gray-500 text-sm">
         19 MAY 12:56
        </p>
        <button class="bg-red-500 text-white px-3 py-1 rounded-lg">
         Reject
        </button>
        <button class="bg-green-500 text-white px-3 py-1 rounded-lg">
         Approve
        </button>
       </div>
      </div>
      <div class="flex items-center justify-between">
       <div class="flex items-center">
        <img alt="User avatar" class="w-10 h-10 rounded-full" height="40" src="https://storage.googleapis.com/a1aa/image/eNgDP2iQxBwfoEi8AuzfKGN6Ib4gheWm2BL3WqN14K4yNN4PB.jpg" width="40"/>
        <div class="ml-4">
         <p class="font-semibold">
          Albert Andersen
         </p>
         <p class="text-gray-500 text-sm">
          Lorem Ipsum is simply...
         </p>
        </div>
       </div>
       <div class="flex items-center space-x-4">
        <p class="text-gray-500 text-sm">
         21 JULY 15:45
        </p>
        <button class="bg-red-500 text-white px-3 py-1 rounded-lg">
         Reject
        </button>
        <button class="bg-green-500 text-white px-3 py-1 rounded-lg">
         Approve
        </button>
       </div>
      </div>
     </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
     <div class="bg-white p-6 rounded-lg shadow">
      <h2 class="text-xl font-semibold mb-4">
       Upcoming Event
      </h2>
      <div class="flex items-center justify-between">
       <div>
        <p class="text-3xl font-semibold">
         45
        </p>
        <p class="text-gray-500">
         Competitors
        </p>
       </div>
       <div class="text-purple-500 text-4xl">
        <i class="fas fa-hand-peace">
        </i>
       </div>
      </div>
      <div class="mt-4">
       <p class="text-gray-500">
        You can participate in event
       </p>
       <div class="h-2 bg-purple-500 rounded-full mt-2" style="width: 34%;">
       </div>
      </div>
     </div>
     <div class="bg-white p-6 rounded-lg shadow">
      <div class="flex items-center justify-between mb-4">
       <div>
        <p class="text-3xl font-semibold">
         235
        </p>
        <p class="text-gray-500">
         Total Ideas
        </p>
       </div>
       <div class="text-green-500 text-4xl">
        <i class="fas fa-lightbulb">
        </i>
       </div>
      </div>
      <div class="flex items-center justify-between">
       <div>
        <p class="text-3xl font-semibold">
         26
        </p>
        <p class="text-gray-500">
         Total Locations
        </p>
       </div>
       <div class="text-blue-500 text-4xl">
        <i class="fas fa-map-marker-alt">
        </i>
       </div>
      </div>
     </div>
    </div>
   </div>
  </div>
 </body>
</html>

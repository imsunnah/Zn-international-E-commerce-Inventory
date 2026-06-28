<?php
foreach(\App\Models\Category::all() as $c) { $c->slug = trim(preg_replace('/\s+/', '', strtolower($c->slug))); $c->save(); }
foreach(\App\Models\SubCategory::all() as $c) { $c->slug = trim(preg_replace('/\s+/', '', strtolower($c->slug))); $c->save(); }
foreach(\App\Models\Brand::all() as $c) { $c->slug = trim(preg_replace('/\s+/', '', strtolower($c->slug))); $c->save(); }
foreach(\App\Models\Product::all() as $c) { $c->slug = trim(preg_replace('/\s+/', '', strtolower($c->slug))); $c->save(); }
echo "Finished trimming slugs successfully.\n";

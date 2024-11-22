<template>
    <div class="w-screen h-screen mx-auto p-4 bg-gray-50">
        <h1 class="text-3xl font-bold mb-4 text-center">Books</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <Card
                v-for="book in booksData"
                :key="book.id"
                class="bg-white shadow-md rounded-lg p-4 hover:shadow-lg transition-shadow"
            >
                <img
                    v-if="book.cover"
                    :src="book.cover"
                    alt="Book Cover"
                    class="rounded-md mb-4 w-full h-48 object-cover"
                />
                <CardHeader>
                    <CardTitle>{{ book.title }}</CardTitle>
                    <CardDescription>By {{ book.author?.name || 'Unknown' }}</CardDescription>
                </CardHeader>
                <CardContent>
                    <p class="text-sm text-gray-700">{{ book.description || 'No description available.' }}</p>
                    <p class="mt-2 text-xs text-gray-500">
                        <strong>Language:</strong> {{ book.language || 'N/A' }} |
                        <strong>ISBN:</strong> {{ book.isbn || 'N/A' }}
                    </p>
                </CardContent>
                <CardFooter v-if="book.category" class="mt-4 flex justify-between items-center">
                    <Badge variant="outline">{{ book.category?.name }}</Badge>
                    <Button variant="secondary" size="sm">View More</Button>
                </CardFooter>
            </Card>
            <div ref="last" class="-translate-y-32"></div>
        </div>
    </div>
</template>

<script setup>
import {
    Card,
    CardHeader,
    CardTitle,
    CardContent,
    CardFooter,
} from "@/shadcn/ui/card";
import {Button} from "@/shadcn/ui/button";
import {Badge} from "@/shadcn/ui/badge";
import {CardDescription} from "@/shadcn/ui/card/index.js";
import {useIntersectionObserver} from '@vueuse/core';
import axios from 'axios';
import {ref, reactive} from 'vue';

// Props definition
const props = defineProps({
    books: {
        type: Object,
        required: true,
    },
});

// State management for books data
const booksData = reactive([...props.books.data]);
const meta = reactive({...props.books.meta});

// Reference for the last element
const last = ref(null);

// Intersection observer to fetch more books
const {stop} = useIntersectionObserver(last, ([{isIntersecting}]) => {
    if (!isIntersecting || !meta.next_cursor) {
        return;
    }

    fetchBooks();
});

// Function to fetch more books
const fetchBooks = async () => {
    try {
        const response = await axios.get(`${meta.path}?cursor=${meta.next_cursor}`);
        booksData.push(...response.data.data);
        Object.assign(meta, response.data.meta);

        // Stop observing if there's no next cursor
        if (!meta.next_cursor) stop();
    } catch (error) {
        console.error("Error fetching books:", error);
    }
};
</script>

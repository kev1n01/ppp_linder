<script setup lang="ts">
import { SidebarGroup, SidebarGroupLabel, SidebarMenu, SidebarMenuButton, SidebarMenuItem, SidebarMenuSub, SidebarMenuSubItem, SidebarMenuSubButton } from '@/components/ui/sidebar';
import { type NavItem, type SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import {
    Collapsible,
    CollapsibleContent,
    CollapsibleTrigger,
} from '@/components/ui/collapsible'
import { ChevronRight } from 'lucide-vue-next';

defineProps<{
    items: NavItem[];
}>();

const page = usePage<SharedData>();
</script>
<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarGroupLabel></SidebarGroupLabel>
        <SidebarMenu>
            <!-- Elementos NO colapsables -->
            <SidebarMenuItem v-for="item in items.filter(i => !i.isCollapsible || !i.items?.length)" :key="item.title">
                <SidebarMenuButton as-child :is-active="item.href === page.url" :tooltip="item.title">
                    <Link :href="item.href || '#'">
                        <component :is="item.icon" />
                        <span>{{ item.title }}</span>
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>

            <!-- Elementos colapsables -->
            <Collapsible v-for="item in items.filter(i => i.isCollapsible && i.items?.length)" :key="item.title" as-child>
                <SidebarMenuItem>
                    <CollapsibleTrigger as-child>
                        <SidebarMenuButton :tooltip="item.title"  :is-active="item.href === page.url">
                            <component :is="item.icon" />
                            <span>{{ item.title }}</span>
                            <ChevronRight class="ml-auto transition-transform duration-200 group-data-[state=open]/collapsible:rotate-90" />
                        </SidebarMenuButton>
                    </CollapsibleTrigger>
                    <CollapsibleContent>
                        <SidebarMenuSub>
                            <SidebarMenuSubItem v-for="subItem in item.items" :key="subItem.title">
                                <SidebarMenuSubButton as-child  :is-active="subItem.href === page.url">
                                    <a :href="subItem.href">
                                        <span>{{ subItem.title }}</span>
                                    </a>
                                </SidebarMenuSubButton>
                            </SidebarMenuSubItem>
                        </SidebarMenuSub>
                    </CollapsibleContent>
                </SidebarMenuItem>
            </Collapsible>
        </SidebarMenu>
    </SidebarGroup>
</template>
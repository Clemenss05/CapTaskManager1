# 📚 Capstone Collaborative Task Manager: Project Context

This document outlines the scope, objective, and technical strategy for the **Capstone Collaborative Task Manager (C-CTM)**, a specialized project management system designed to support thesis and capstone research teams.

---

## 1. 💡 OVERVIEW & GOALS

### 1.1. Project Name
Capstone Collaborative Task Manager (C-CTM)

### 1.2. Primary Goal (MVP)
To provide **transparency and accountability** for Capstone teams and their faculty advisors by centralizing task management and progress tracking via an intuitive Kanban board.

### 1.3. Success Criteria
The project is successful if it meets these criteria:
* A team of 3-5 students and 1 advisor can manage a project from start to finish.
* The Advisor can instantly view the progress (tasks completed vs. total tasks) of all assigned teams.
* The core drag-and-drop Kanban functionality is stable and reactive.

---

## 2. ❓ PROBLEM & SOLUTION (Value Proposition)

### 2.1. The Problem
The current capstone research process lacks a standardized, real-time method for teams to track their work and for faculty advisors to monitor dozens of projects efficiently. This leads to:
* **Lack of Team Accountability:** Unclear task assignments and slow progress visibility.
* **Inefficient Advising:** Advisors spend too much time getting status updates instead of providing critical feedback.
* **Documentation Gaps:** Task history and decisions are lost in chat messages or private documents.

### 2.2. The Solution (C-CTM)
C-CTM provides a singular source of truth for all project tasks and updates.

| Feature | Value to User |
| :--- | :--- |
| **Kanban Board** (Core MVP) | Real-time visual tracking of progress and bottlenecks. |
| **Role-Based Access** | Ensures Advisors have oversight and students focus on execution. |
| **Task Commenting** | Centralizes decision logs and feedback directly on the work item. |
| **Simple Metrics** | Allows advisors to triage their attention to projects falling behind. |

---

## 3. ⚙️ TECHNICAL ARCHITECTURE (Strategy)

### 3.1. Tech Stack
The project uses the **PHP/Laravel Stack** chosen for its rapid development capabilities and structural elegance.

| Component | Technology | Rationale |
| :--- | :--- | :--- |
| **Backend Framework** | **Laravel (PHP)** | Robust, scalable, and provides built-in features (Eloquent, Routing). |
| **Interactivity** | **Laravel Livewire** | **Critical for the MVP.** Allows for dynamic, reactive UI (drag-and-drop Kanban) without complex frontend API development. |
| **Styling** | **Tailwind CSS** | Utility-first design for quick, professional-looking, responsive interfaces. |
| **Database** | **MySQL** | Standard, reliable relational database for data integrity. |

### 3.2. Data Model Summary
The system revolves around four core models:
1.  **`User`**: Manages authentication and roles (Advisor, Leader, Member).
2.  **`Project`**: The main container for a single Capstone research project.
3.  **`Task`**: The core unit of work, linked to a Project, an Assignee, and a Status (`todo`, `in-progress`, `done`).
4.  **`Comment`**: Provides historical context and decision logs for each Task.

### 3.3. Implementation Strategy
* **Phase 1 (MVP Foundation):** Implement Laravel Breeze/Jetstream for Authentication and establish the core database structure (`User`, `Project`, `Task` migrations).
* **Phase 2 (Core Feature):** Develop the **`KanbanBoard` Livewire component**. This component will handle task loading, rendering, and the crucial `wire:sortable` logic to update the database when cards are dragged and dropped between status columns.
* **Phase 3 (Finalizing):** Implement the `TaskModal` for creating/editing tasks, and apply Laravel Gates/Policies to ensure role-based permissions are enforced.
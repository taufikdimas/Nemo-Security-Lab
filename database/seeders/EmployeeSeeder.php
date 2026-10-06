<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', '!=', 'service')->orderBy('id')->get()->keyBy('email');
        $admin = $users['admin@garuda-siber.internal'];
        $staff = $users['user@garuda-siber.internal'];

        $employees = [
            ['EMP-0001', 'Budi Santoso', 'budi.santoso@garuda-siber.internal', 'IT', 'Security Analyst', '081234567890', '2021-03-01'],
            ['EMP-0002', 'Siti Rahayu', 'siti.rahayu@garuda-siber.internal', 'Penetration Testing', 'Pentester', '081298765432', '2021-07-15'],
            ['EMP-0003', 'Andi Wijaya', 'andi.wijaya@garuda-siber.internal', 'Forensics', 'Forensic Analyst', '081211223344', '2022-01-10'],
            ['EMP-0004', 'Dewi Anggraini', 'dewi.anggraini@garuda-siber.internal', 'Security Operations Center', 'SOC Analyst', '081233445566', '2022-04-01'],
            ['EMP-0005', 'Rizky Ramadhan', 'rizky.ramadhan@garuda-siber.internal', 'Penetration Testing', 'Senior Pentester', '081277889900', '2020-09-01'],
            ['EMP-0006', 'Sinta Maharani', 'sinta.maharani@garuda-siber.internal', 'Cloud Security', 'Cloud Security Engineer', '081223344556', '2023-02-01'],
            ['EMP-0007', 'Agus Prasetyo', 'agus.prasetyo@garuda-siber.internal', 'Network Security', 'Network Security Engineer', '081299887766', '2021-11-01'],
            ['EMP-0008', 'Putri Lestari', 'putri.lestari@garuda-siber.internal', 'Governance Risk Compliance', 'GRC Analyst', '081210987654', '2023-06-01'],
            ['EMP-0009', 'Hendra Kusuma', 'hendra.kusuma@garuda-siber.internal', 'Malware Analysis', 'Malware Analyst', '081234567001', '2022-09-01'],
            ['EMP-0010', 'Maya Sari', 'maya.sari@garuda-siber.internal', 'Identity and Access Management', 'IAM Engineer', '081232323454', '2023-08-15'],
            ['EMP-0011', 'Fajar Nugroho', 'fajar.nugroho@garuda-siber.internal', 'Red Team', 'Red Team Operator', '081255667788', '2024-01-15'],
            ['EMP-0012', 'Ratna Kumala', 'ratna.kumala@garuda-siber.internal', 'Cloud Security', 'Cloud Architect', '081277776655', '2020-05-01'],
            ['EMP-0013', 'Bayu Saputra', 'bayu.saputra@garuda-siber.internal', 'Incident Response', 'Incident Responder', '081244556677', '2024-03-01'],
            ['EMP-0014', 'Nurul Hidayah', 'nurul.hidayah@garuda-siber.internal', 'Governance Risk Compliance', 'Compliance Officer', '081266778899', '2023-10-01'],
            ['EMP-0015', 'Yoga Pratama', 'yoga.pratama@garuda-siber.internal', 'Security Operations Center', 'SOC Team Lead', '081211445566', '2021-06-01'],
        ];

        foreach ($employees as $i => [$number, $name, $email, $department, $position, $phone, $joined]) {
            Employee::create([
                'employee_number' => $number,
                'name' => $name,
                'email' => $email,
                'department' => $department,
                'position' => $position,
                'phone' => $phone,
                // Seeded rows intentionally carry no portrait file; the detail
                // view falls back to initials when photo is empty.
                'photo' => null,
                'joined_at' => $joined,
                'created_by' => $i % 2 === 0 ? $admin->id : $staff->id,
            ]);
        }
    }
}

<?php

namespace Tests\Repositories;

use App\Models\Student;
use App\Repositories\StudentRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class StudentRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    protected StudentRepository $studentRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->studentRepo = app(StudentRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_student()
    {
        $student = Student::factory()->make()->toArray();

        $createdStudent = $this->studentRepo->create($student);

        $createdStudent = $createdStudent->toArray();
        $this->assertArrayHasKey('id', $createdStudent);
        $this->assertNotNull($createdStudent['id'], 'Created Student must have id specified');
        $this->assertNotNull(Student::find($createdStudent['id']), 'Student with given id must be in DB');
        $this->assertModelData($student, $createdStudent);
    }

    /**
     * @test read
     */
    public function test_read_student()
    {
        $student = Student::factory()->create();

        $dbStudent = $this->studentRepo->find($student->id);

        $dbStudent = $dbStudent->toArray();
        $this->assertModelData($student->toArray(), $dbStudent);
    }

    /**
     * @test update
     */
    public function test_update_student()
    {
        $student = Student::factory()->create();
        $fakeStudent = Student::factory()->make()->toArray();

        $updatedStudent = $this->studentRepo->update($fakeStudent, $student->id);

        $this->assertModelData($fakeStudent, $updatedStudent->toArray());
        $dbStudent = $this->studentRepo->find($student->id);
        $this->assertModelData($fakeStudent, $dbStudent->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_student()
    {
        $student = Student::factory()->create();

        $resp = $this->studentRepo->delete($student->id);

        $this->assertTrue($resp);
        $this->assertNull(Student::find($student->id), 'Student should not exist in DB');
    }
}
